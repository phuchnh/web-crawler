<?php

namespace App\Commands;

libxml_use_internal_errors(true);

use phpQuery;
use \TypeRocket\Console\Command;

class CrawlLink extends Command
{
    /**
     * @var string
     */
    protected $domain_url;
    
    protected $command = [
        'app:craw-link',
        'Short description here',
        'Longer help text goes here.',
    ];
    
    protected function config()
    {
        // If you want to accept arguments
        // $this->addArgument('arg', self::REQUIRED, 'Description');
    }
    
    /**
     * @throws \Exception
     */
    public function exec()
    {
        $settings          = $this->getCategorySettings();
        $categoryIds       = array_pluck($settings, 'category_id');
        $categoryUrls      = $this->getCategoryUrls($categoryIds);
        $categorySelectors = $this->getCategorySelectors();
        $categorySettings  = $this->mapSelectors($categoryUrls, $categorySelectors);
        $categorySettings  = $this->mapPagination($settings, $categorySettings);
        
        $index = 0;
        do {
            $url              = array_get($categorySettings[$index], 'category_url');
            $selector         = array_get($categorySettings[$index], 'selector');
            $single_options   = array_get($categorySettings[$index], 'single_options');
            $this->domain_url = array_get($categorySettings[$index], 'domain_url');
            
            try {
                $html = phpQuery::newDocumentFileHTML($url);
            } catch (\Exception $exception) {
                continue;
            }
            
            if ( ! $html) {
                continue;
            }
            
            try {
                $links = [];
                foreach (pq($selector)->elements as $element) {
                    /**@var $element \DOMElement */
                    $links[] = sprintf("('%s', '%s')", $this->link($element->getAttribute('href')),
                        esc_sql($single_options));
                }
            } catch (\Exception $exception) {
                continue;
            }
            
            $this->createMany($links);
            $this->deleteDuplicateRows();
            
            // Next
            $index++;
        } while ($index < count($categorySettings));
        
        // When command executes
        echo 'Success';
    }
    
    /**
     * Create links by pagination
     *
     * @param  array  $settings
     * @param  array  $categories
     *
     * @return array
     */
    protected function mapPagination(array $settings, array $categories)
    {
        $result = [];
        $index  = 0;
        while ($index < count($categories)) {
            $category = $categories[$index];
            
            $matched = array_search($category['category_id'], array_column($settings, 'category_id'), true);
            
            if ($matched > -1) {
                $limit = (int)array_get($settings[$matched], 'page', 1);
                $links = [];
                for ($i = 1; $i <= $limit; $i++) {
                    $value                 = $categories[$matched];
                    $value['category_url'] = $value['category_url'].'?page='.$i;
                    $links[]               = $value;
                }
                
                $result = array_merge($result, $links);
            }
            $index++;
        }
        
        return $result;
    }
    
    /**
     * @param  array  $categoryUrls
     * @param  array  $categorySelectors
     *
     * @return array
     */
    protected function mapSelectors(array $categoryUrls, array $categorySelectors)
    {
        $result = [];
        foreach ($categoryUrls as $key => $value) {
            $haystack = array_column($categorySelectors, 'id');
            $needle   = $value['crawl_domain_id'];
            $matched  = array_search($needle, $haystack, true);
            if ($matched > -1) {
                $value['selector']       = array_get($categorySelectors[$matched], 'archive_options.selector');
                $value['single_options'] = array_get($categorySelectors[$matched], 'single_options');
                $value['domain_url']     = array_get($categorySelectors[$matched], 'domain_url');
                $result[]                = $value;
            }
        }
        
        return $result;
    }
    
    /**
     * @return array
     * @throws \Exception
     */
    protected function getCategorySelectors()
    {
        $result  = [];
        $domains = tr_get_model('CrawlDomain')
            ->findAll()
            ->select('id', 'archive_options', 'single_options', 'domain_url')
            ->get();
        
        foreach ((array)$domains as $domain) {
            $value['id']              = $domain->id;
            $value['domain_url']      = $domain->domain_url;
            $value['archive_options'] = $domain->archive_options;
            $value['single_options']  = array_get($domain->getPropertiesUnaltered(), 'single_options');
            $result[]                 = $value;
        }
        
        return $result;
    }
    
    /**
     * @param  array  $categoryIds
     *
     * @return array
     * @throws \Exception
     */
    protected function getCategoryUrls(array $categoryIds = [])
    {
        $result     = [];
        $categories = tr_get_model('CrawlCategory')
            ->where('id', 'IN', $categoryIds)
            ->select('crawl_domain_id', 'category_url', 'id')
            ->get();
        
        foreach ($categories as $category) {
            $value['crawl_domain_id'] = data_get($category, 'crawl_domain_id');
            $value['category_url']    = data_get($category, 'category_url');
            $value['category_id']     = data_get($category, 'id');
            $result[]                 = $value;
        }
        
        return $result;
    }
    
    /**
     * Insert multiple row
     *
     * @param  array  $links
     */
    protected function createMany(array $links)
    {
        global $wpdb;
        $crawl_link_table = $wpdb->prefix.'crawl_links';
        $sql              = "INSERT INTO {$crawl_link_table} (`link`, `options`) VALUES ".implode(',', $links).';';
        $wpdb->query($sql);
    }
    
    protected function deleteDuplicateRows()
    {
        global $wpdb;
        $crawl_link_table = $wpdb->prefix.'crawl_links';
        $sql              = "DELETE t1 FROM {$crawl_link_table} t1 INNER JOIN {$crawl_link_table} t2 WHERE t1.id < t2.id AND t1.link = t2.link;";
        $wpdb->query($sql);
    }
    
    
    /**
     * @return array
     * @throws \Exception
     */
    protected function getCategorySettings()
    {
        // Get all crawl settings
        $settings = tr_get_model('CrawlSetting');
        $settings = (array)$settings->findAll()->select('categories')->get();
        $result   = [];
        
        // Map category_id and pagination
        foreach ($settings as $setting) {
            /**@var $setting \App\Models\CrawlSetting */
            $result = array_merge($result, $setting->categories);
        }
        
        return $result;
    }
    
    /**
     * @param $url string
     *
     * @return string
     */
    private function link(string $url)
    {
        if (preg_match('/^(https?:\/\/).*$/i', $url) > 0) {
            return $url;
        }
        
        $scheme = parse_url($this->domain_url, PHP_URL_SCHEME);
        $host   = parse_url($this->domain_url, PHP_URL_HOST);
        
        return sprintf('%s://%s/%s', $scheme, $host, trim($url, '/'));
    }
}
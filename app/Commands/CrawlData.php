<?php

namespace App\Commands;

libxml_use_internal_errors(true);

use phpQuery;
use \TypeRocket\Console\Command;

class CrawlData extends Command
{
    /**
     * @var string
     */
    protected $domain_url;
    
    protected $command = [
        'app:crawl',
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
        $categoryIds       = $this->getCategoryIds();
        $categoryUrls      = $this->getCategoryUrls($categoryIds);
        $categorySelectors = $this->getCategorySelectors();
        $categorySettings  = $this->mapSelectors($categoryUrls, $categorySelectors);
        
        $index = 0;
        do {
            $url              = array_get($categorySettings[$index], 'category_url');
            $selector         = array_get($categorySettings[$index], 'selector');
            $this->domain_url = array_get($categorySettings[$index], 'domain_url');
            phpQuery::newDocumentFileHTML($url);
            
            $links = pq($selector)->map(function (\DOMElement $element) {
                return sprintf('("%s", NULL)',
                    $this->link($element->getAttribute('href'))
                );
            });
            
            $links = $links->elements;
            
            $this->createMany($links);
            
            // Next
            $index++;
        } while ($index < count($categorySettings));
        
        // When command executes
        $this->success('Execute!');
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
            $matched  = array_search($needle, $haystack);
            if ($matched > -1) {
                $value['selector']   = array_get($categorySelectors[$matched], 'archive_options.selector');
                $value['selector']   = array_get($categorySelectors[$matched], 'archive_options.selector');
                $value['domain_url'] = array_get($categorySelectors[$matched], 'domain_url');
                $result[]            = $value;
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
        $domains = (new \App\Models\CrawlDomain())->findAll()->select('id', 'archive_options', 'domain_url')->get();
        
        foreach ((array)$domains as $domain) {
            $value['id']              = $domain->id;
            $value['domain_url']      = $domain->domain_url;
            $value['archive_options'] = $domain->archive_options;
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
        $categories = (new \App\Models\CrawlCategory())->where('id', 'IN', $categoryIds)
                                                       ->select('crawl_domain_id', 'category_url')
                                                       ->get();
        
        foreach ($categories as $category) {
            $value['crawl_domain_id'] = $category->crawl_domain_id;
            $value['category_url']    = $category->category_url;
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
        $sql              = "INSERT INTO {$crawl_link_table} (link, options) VALUES ".implode(',', $links).";";
        require_once ABSPATH.'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
    
    
    /**
     * @return array
     * @throws \Exception
     */
    protected function getCategoryIds()
    {
        // Get crawl settings
        $settings = new \App\Models\CrawlSetting();
        $settings = $settings->findAll()->select('categories')->get();
        
        // Get categories url
        $categoru_urls = [];
        foreach ((array)$settings as $setting) {
            $categoru_urls[] = array_column($setting->categories, 'category_url');
        }
        
        return array_flatten($categoru_urls);
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
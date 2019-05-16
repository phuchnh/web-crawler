<?php

namespace App\Commands;

use App\Models\CrawlCategory;
use App\Models\CrawlDomain;
use App\Models\CrawlSetting;
use \TypeRocket\Console\Command;

class CrawlData extends Command
{
    
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
        
        dd($categorySettings);
        
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
                $value['selector'] = array_get($categorySelectors[$matched], 'archive_options.selector');
                $result[]          = $value;
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
        $domains = (new CrawlDomain)->findAll()->select('id', 'archive_options')->get();
        
        foreach ((array)$domains as $domain) {
            $value['id']              = $domain->id;
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
        $categories = (new CrawlCategory)->where('id', 'IN', $categoryIds)
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
     * @return array
     * @throws \Exception
     */
    protected function getCategoryIds()
    {
        // Get crawl settings
        $settings = new CrawlSetting();
        $settings = $settings->findAll()->select('categories')->get();
        
        // Get categories url
        $categoru_urls = [];
        foreach ((array)$settings as $setting) {
            $categoru_urls[] = array_column($setting->categories, 'category_url');
        }
        
        return array_flatten($categoru_urls);
    }
}
<?php

namespace App\Controllers;

use App\Models\CrawlDomain;
use App\Models\CrawlPreview;
use \TypeRocket\Controllers\Controller;
use TypeRocket\Http\Request;

class CrawlPreviewController extends Controller
{
    protected $modelClass = CrawlPreview::class;
    protected $resource = 'crawl_preview';
    
    public function config()
    {
        return tr_view('crawl_preview.config', [
            'form' => tr_form($this->resource, 'handle'),
        ]);
    }
    
    /**
     * @return \TypeRocket\Http\Response
     * @throws \Exception
     */
    public function handle()
    {
        
        /**
         * @var $domain \App\Models\CrawlDomain
         */
        $domain = new CrawlDomain();
        $domain = $domain->findById($this->request->getFields('domain_id'));
        if ($domain === null) {
            $this->response->exitNotFound();
            
            return $this->response;
        }
        
        /**
         * @var $category \App\Models\CrawlCategory
         */
        $category = $domain->categories()->findById($this->request->getFields('category_id'));
        if ($category === null) {
            $this->response->exitNotFound();
            
            return $this->response;
        }
    
        phpQuery::newDocumentFile($category->category_url);
        
        $this->response->setData('dasd', $this->request->getFields());
        $this->response->exitJson(200);
        
        return $this->response;
    }
}

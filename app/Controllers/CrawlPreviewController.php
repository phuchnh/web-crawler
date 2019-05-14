<?php

namespace App\Controllers;

use App\Models\CrawlPreview;
use \TypeRocket\Controllers\Controller;
use TypeRocket\Http\Request;

class CrawlPreviewController extends Controller
{
    protected $modelClass = CrawlPreview::class;
    protected $resource = 'crawl_preview';
    
    public function config(Request $request)
    {
        return tr_view('crawl_preview.config', [
            'form' => tr_form($this->resource, 'handle'),
        ]);
    }
    
    public function handle(Request $request)
    {
        
        $this->response->setData('dasd', $request->getFields());
        $this->response->exitJson(200);
        
        return $this->response;
    }
}

<?php

namespace App\Controllers;

use App\Models\CrawlPreview;
use \TypeRocket\Controllers\Controller;

class CrawlPreviewController extends Controller
{
    
    protected $modelClass = CrawlPreview::class;
    protected $resource = 'options';
    
    public function config()
    {
        return tr_view('crawl_preview.config', ['form' => tr_form('crawl_preview', 'hanlde')]);
    }
    
    public function hanlde()
    {
        $options = [
            'domain_id'   => 'required',
            'category_id' => 'required',
        ];
        
        $validator = tr_validator($options, $this->request->getFields());
        
        if ($validator->getErrors()) {
            $this->response->exitNotFound();
            
            return $this->response;
        }
        
        $this->response->setMessage('Cái lề gì thốn');
        $this->response->withFields($this->request->getFields());
        
        return tr_redirect()->toPage($this->resource, 'config')->with($this->request->getFields());
        
    }
}
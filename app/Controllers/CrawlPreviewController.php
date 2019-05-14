<?php

namespace App\Controllers;

use \TypeRocket\Controllers\Controller;
use TypeRocket\Http\Response;

class CrawlPreviewController extends Controller
{
    
    public function config()
    {
        return tr_view('crawl_preview.config', ['form' => tr_form('auto', 'hanlde')]);
    }
    
    public function hanlde()
    {
        $request = new \TypeRocket\Http\Request();
        
        $options = [
            'domain_id'   => 'required',
            'category_id' => 'required',
        ];
        
        $validator = tr_validator($options, $request->getFields());
        
        if ($validator->getErrors()) {
            $this->response->exitNotFound();
            
            return $this->response;
        }
    
        $response = new \TypeRocket\Http\Response();
        $response->setData('asdsa', '123123');
        
        return $response;
    
    }
}
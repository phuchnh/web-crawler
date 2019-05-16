<?php

namespace App\Controllers;

use App\Models\CrawlSetting;
use \TypeRocket\Controllers\Controller;

class CrawlSettingController extends Controller
{
    
    protected $modelClass = CrawlSetting::class;
    
    protected $resource = 'crawl_setting';
    
    /**
     * The index page for admin
     *
     * @return mixed
     */
    public function index()
    {
        return tr_view('crawl_setting.index');
    }
    
    /**
     * The add page for admin
     *
     * @return mixed
     */
    public function add()
    {
        $form = tr_form($this->resource, 'create');
        
        return tr_view('crawl_setting.add', ['form' => $form]);
    }
    
    /**
     * Create item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @return mixed
     * @throws \Exception
     */
    public function create()
    {
        $options = [
            'crawl_domain_id' => 'required|unique:crawl_domain_id:wp_crawl_settings',
        ];
        
        $validator = tr_validator($options, $this->request->getFields());
        
        $validator->setErrorMessages([
            'crawl_domain_id:required' => 'Domain is require.',
            'crawl_domain_id:unique'   => 'Domain is taken.',
        ])->validate();
        
        if ($validator->getErrors()) {
            $validator->flashErrors($this->response);
            
            return tr_redirect()->toPage($this->resource, 'add')
                                ->withFields($this->request->getFields());
        }
        
        $crawl_setting                  = new CrawlSetting();
        $crawl_setting->crawl_domain_id = $this->request->getFields('crawl_domain_id');
        $crawl_setting->categories      = $this->request->getFields('categories');
        // $crawl_setting->options         = $this->request->getFields('options');
        $crawl_setting->save();
        $this->response->flashNext('Success!');
        
        return tr_redirect()->toPage($this->resource, 'index');
    }
    
    /**
     * The edit page for admin
     *
     * @param $id
     *
     * @return mixed
     */
    public function edit($id)
    {
        $form = tr_form($this->resource, 'update', $id);
        
        return tr_view('crawl_setting.edit', ['form' => $form]);
    }
    
    /**
     * Update item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param  \App\Models\CrawlSetting  $crawl_setting
     *
     * @return mixed
     * @throws \Exception
     */
    public function update(CrawlSetting $crawl_setting)
    {
        
        $crawl_setting->crawl_domain_id = $this->request->getFields('crawl_domain_id');
        $crawl_setting->categories      = $this->request->getFields('categories');
        // $crawl_setting->options         = $this->request->getFields('options');
        $crawl_setting->save();
        $this->response->flashNext('Success!');
        
        return tr_redirect()->toPage($this->resource, 'index');
    }
    
    /**
     * The show page for admin
     *
     * @param $id
     *
     * @return mixed
     */
    public function show($id)
    {
        // TODO: Implement show() method.
    }
    
    /**
     * The delete page for admin
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
    
    /**
     * Destroy item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
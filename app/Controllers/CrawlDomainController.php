<?php

namespace App\Controllers;

use App\Models\CrawlDomain;
use \TypeRocket\Controllers\Controller;

class CrawlDomainController extends Controller
{
    
    protected $modelClass = CrawlDomain::class;
    protected $resource = 'crawl_domain';
    
    /**
     * The index page for admin
     *
     * @return mixed
     */
    public function index()
    {
        return tr_view('crawl_domain.index');
    }
    
    /**
     * The add page for admin
     *
     * @return mixed
     */
    public function add()
    {
        $form = tr_form($this->resource, 'create');
        
        return tr_view('crawl_domain.add', ['form' => $form]);
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
        $fields = $this->request->getFields();
        
        $options = [
            'domain_url' => 'required|unique:domain_url:wp_crawl_domains',
        ];
        
        $validator = tr_validator($options, $fields);
        
        if ($validator->getErrors()) {
            $validator->flashErrors($this->response);
            
            return tr_redirect()->toPage($this->resource, 'add')->withFields($fields);
        }
        
        $crawl_domain             = new CrawlDomain;
        $crawl_domain->domain_url = $fields['domain_url'];
        
        if ( ! $success = (boolean)$crawl_domain->save()) {
            $this->response->flashNext('Failure!', 'error');
            
            return tr_redirect()->toPage($this->resource, 'add')->withFields($fields);
        }
        $this->response->flashNext('Success!');
        
        return tr_redirect()->toPage($this->resource, 'index');
    }
    
    /**
     * The edit page for admin
     *
     * @param  string  $id
     *
     * @return mixed
     */
    public function edit($id)
    {
        $form = tr_form($this->resource, 'update', $id);
        
        return tr_view('crawl_domain.edit', ['form' => $form]);
    }
    
    /**
     * The edit page for admin
     *
     * @param  string  $id
     *
     * @return mixed
     */
    public function archive($id)
    {
        $form = tr_form($this->resource, 'update', $id);
        
        return tr_view('crawl_domain.archive-option', ['form' => $form]);
    }
    
    /**
     * The edit page for admin
     *
     * @param  string  $id
     *
     * @return mixed
     */
    public function single($id)
    {
        $form = tr_form($this->resource, 'update', $id);
        
        return tr_view('crawl_domain.single-option', ['form' => $form]);
    }
    
    /**
     * Update item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param  CrawlDomain  $crawl_domain
     *
     * @return mixed
     * @throws \Exception
     */
    public function update(CrawlDomain $crawl_domain)
    {
        $options = [
            'domain_url' => 'required|unique:domain_url:wp_crawl_domains@id:'.$crawl_domain->id,
        ];
        
        $validator = tr_validator($options, $this->request->getFields());
        
        if ($validator->getErrors()) {
            $validator->flashErrors($this->response);
            $this->response->withFields($this->request->getFields());
            
            return tr_redirect()->toUrl($this->request->getUri());
        }
        
        $crawl_domain->domain_url = $this->request->getFields('domain_url');
        
        if (array_key_exists('archive_options', $this->request->getFields())) {
            $crawl_domain->archive_options = $this->request->getFields('archive_options');
        }
    
        if (array_key_exists('single_options', $this->request->getFields())) {
            $crawl_domain->single_options = $this->request->getFields('single_options');
        }
        
        $crawl_domain->save();
        $this->response->flashNext('Success!');
    
        return tr_redirect()->toUrl($this->request->getUri());
    }
    
    /**
     * Destroy item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param  CrawlDomain  $crawl_domain
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy(CrawlDomain $crawl_domain)
    {
        if ( ! $success = (boolean)$crawl_domain->delete()) {
            return $this->response->flashNext('Doamin deleted failure!', 'error');
        }
        $this->response->flashNext('Doamin deleted!');
        
        return tr_redirect()->toPage($this->resource, 'index');
    }
}
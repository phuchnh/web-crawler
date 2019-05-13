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
        $request = new \TypeRocket\Http\Request();

        $options = [
            'domain_url' => 'required|unique:domain_url:wp_crawl_domains',
        ];

        $validator = tr_validator($options, $request->getFields());

        if ($validator->getErrors()) {
            $validator->flashErrors($this->response);

            return tr_redirect()->toPage($this->resource, 'add')
                                ->withFields($request->getFields());
        }

        $crawl_domain                 = new CrawlDomain;
        $crawl_domain->domain_url     = $this->request->getFields('domain_url');
        $crawl_domain->domain_options = null;
        $crawl_domain->save();
        $this->response->flashNext('Doamin created!');

        return tr_redirect()->toPage($this->resource, 'index');
    }

    /**
     * The edit page for admin
     *
     * @param string $id
     *
     * @return mixed
     */
    public function edit($id)
    {
        $form = tr_form($this->resource, 'update', $id);

        return tr_view('crawl_domain.edit', ['form' => $form]);
    }

    /**
     * Update item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param CrawlDomain $crawl_domain
     *
     * @return mixed
     * @throws \Exception
     */
    public function update(CrawlDomain $crawl_domain)
    {
        $request = new \TypeRocket\Http\Request();

        $options = [
            'domain_url' => 'required|unique:domain_url:wp_crawl_domains@id:' . $crawl_domain->id,
        ];

        $validator = tr_validator($options, $request->getFields());

        if ($validator->getErrors()) {
            $validator->flashErrors($this->response);

            return tr_redirect()->toPage($this->resource, 'edit', $crawl_domain->id)
                                ->withFields($request->getFields());
        }

        $crawl_domain->domain_url     = $this->request->getFields('domain_url');
        $crawl_domain->domain_options = null;
        $crawl_domain->save();
        $this->response->flashNext('Doamin updated!');

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
     * The option page for admin
     *
     * @param string $id
     *
     * @return mixed
     */
    public function edit_option($id)
    {
        $form = tr_form($this->resource, 'update_option', $id);

        return tr_view('crawl_domain.option', ['form' => $form]);
    }

    /**
     * The option page for admin
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param CrawlDomain $crawl_domain
     *
     * @return mixed
     * @throws \Exception
     */
    public function update_option(CrawlDomain $crawl_domain)
    {
        $crawl_domain->domain_options = $this->request->getFields('domain_options');
        $crawl_domain->save();
        $this->response->flashNext('Setting updated!');

        return tr_redirect()->toPage($this->resource, 'edit_option', $crawl_domain->id);
    }

    /**
     * Destroy item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param CrawlDomain $crawl_domain
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
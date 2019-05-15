<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/phuchnh
 * @since      1.0.0
 *
 * @package    Web_Crawler
 * @subpackage Web_Crawler/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Web_Crawler
 * @subpackage Web_Crawler/admin
 * @author     phuchnh <huynhngochoangphuc@gmail.com>
 */
class Web_Crawler_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     *
     * @param  string $plugin_name The name of this plugin.
     * @param  string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            'select2',
            'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.css'
        );

        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/web-crawler-admin.css',
            [],
            $this->version
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'sweetalert',
            'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js',
            ['jquery']
        );

        wp_enqueue_script(
            'select2',
            'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js',
            ['jquery']
        );

        wp_enqueue_script(
            'loadingoverlay',
            'https://cdnjs.cloudflare.com/ajax/libs/jquery-loading-overlay/2.1.6/loadingoverlay.min.js',
            ['jquery']
        );

        wp_enqueue_script(
            '_',
            'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.11/lodash.min.js'
        );

        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/web-crawler-admin.js',
            ['jquery'],
            $this->version
        );

    }

    /**
     * Register functions of Typerocket
     */
    public function typerocket_loaded()
    {
        $this->add_crawl_domain_menu();
        $this->add_crawl_category_menu();
        $this->add_crawl_setting_menu();
    }

    public function add_crawl_domain_menu()
    {
        $resource = 'crawl_domain';
        $settings = [
            'capability' => 'administrator',
            'menu'       => 'Domain Setting',
            'position'   => 100,
        ];

        $preview = tr_page('crawl_preview', 'config', __('Crawler preview'), [
            'capability' => 'administrator',
            'menu'       => 'Crawler preview',
            'position'   => 102,
        ])
            ->mapAction('GET', 'config')
            ->mapAction('POST', 'handle')
            ->useController()
            ->setId('crawl_preview');

        $add = tr_page($resource, 'add', __('Add Domain'), $settings)
            ->mapActions(['GET' => 'add', 'POST' => 'create']);

        $delete = tr_page($resource, 'delete', __('Delete Domain'), $settings)
            ->mapActions(['GET' => 'delete', 'DELETE' => 'destroy']);

        $edit = tr_page($resource, 'edit', __('Edit Domain'), $settings)
            ->mapActions(['GET' => 'edit', 'PUT' => 'update']);

        $archive = tr_page($resource, 'archive', __('Archive Options'), $settings)
            ->mapActions(['GET' => 'archive', 'PUT' => 'update']);

        $single = tr_page($resource, 'single', __('Single Options'), $settings)
            ->mapActions(['GET' => 'single', 'PUT' => 'update']);

        $index = tr_page($resource, 'index', __('List Domain'), $settings);

        foreach ([$add, $delete, $edit, $archive, $single, $index] as $page) {
            /** @var \TypeRocket\Register\Page $page */
            $page->useController()->removeMenu()->addNewButton();
        }

        $index->apply($add, $delete, $edit, $archive, $single, $preview)
              ->addNewButton()
              ->setId('domain_setting')
              ->setIcon('sphere');

    }

    public function add_crawl_category_menu()
    {
        $resource = 'crawl_category';

        $settings = [
            'capability' => 'administrator',
            'menu'       => 'Category Setting',
            'position'   => 101,
        ];

        $add = tr_page($resource, 'add', __('Add Category'), $settings)
            ->mapActions(['GET' => 'add', 'POST' => 'create']);

        $delete = tr_page($resource, 'delete', __('Delete Category'), $settings)
            ->mapActions(['GET' => 'delete', 'DELETE' => 'destroy']);

        $edit = tr_page($resource, 'edit', __('Edit Category'), $settings)
            ->mapActions(['GET' => 'edit', 'PUT' => 'update']);

        $index = tr_page($resource, 'index', __('List Category'), $settings);

        foreach ([$add, $delete, $edit, $index] as $page) {
            /** @var \TypeRocket\Register\Page $page */
            $page->useController()->addNewButton()->removeMenu();
        }

        $index->apply($add, $delete, $edit)
              ->addNewButton()
              ->setId('category_setting')
              ->setIcon('sphere');
    }

    public function add_crawl_setting_menu(){
        $resource = 'crawl_setting';

        $settings = [
            'capability' => 'administrator',
            'menu'       => 'Crawler Setting',
            'position'   => 103,
        ];

        $add = tr_page($resource, 'add', __('Add Setting'), $settings)
            ->mapActions(['GET' => 'add', 'POST' => 'create']);

        $delete = tr_page($resource, 'delete', __('Delete Setting'), $settings)
            ->mapActions(['GET' => 'delete', 'DELETE' => 'destroy']);

        $edit = tr_page($resource, 'edit', __('Edit Setting'), $settings)
            ->mapActions(['GET' => 'edit', 'PUT' => 'update']);

        $index = tr_page($resource, 'index', __('List Setting'), $settings);

        foreach ([$add, $delete, $edit, $index] as $page) {
            /** @var \TypeRocket\Register\Page $page */
            $page->useController()->addNewButton()->removeMenu();
        }

        $index->apply($add, $delete, $edit)
              ->addNewButton()
              ->setId('crawl_setting')
              ->setIcon('sphere');
    }

    public function customize_menu_labels()
    {
        global $menu;
        global $submenu;

        // dd($menu, $submenu);
    }

    /**
     * @throws \Exception
     */
    public function handle_crawl_schedule_event()
    {
        $data             = new \App\Models\CrawlDomain();
        $data->domain_url = 'https://bepelegant.vn/handle_crawl_schedule_event';
        $data->save();
    }

}

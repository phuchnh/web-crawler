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
     * @var \TypeRocket\Register\Page
     */
    private $crawl_domain;

    /**
     * @var \TypeRocket\Register\Page
     */
    private $crawl_category;

    /**
     * Initialize the class and set its properties.
     *
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
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
            'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'
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
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/web-crawler-admin.js',
            ['jquery'],
            $this->version
        );

        wp_enqueue_script(
            'select2',
            'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
            ['jquery']
        );

    }

    /**
     * Register functions of Typerocket
     */
    public function typerocket_loaded()
    {
        $this->add_crawl_domain_menu();
        $this->add_crawl_category_menu();
    }

    public function add_crawl_domain_menu()
    {
        $resource = 'crawl_domain';
        $settings = [
            'capability' => 'administrator',
            'position'   => 999,
        ];

        $add = tr_page($resource, 'add', __('Add Domain'), $settings)
            ->mapActions(['GET' => 'add', 'POST' => 'create']);

        $delete = tr_page($resource, 'delete', __('Delete Domain'), $settings)
            ->mapActions(['GET' => 'delete', 'DELETE' => 'destroy']);

        $edit = tr_page($resource, 'edit', __('Edit Domain'), $settings)
            ->mapActions(['GET' => 'edit', 'PUT' => 'update']);

        $edit_option = tr_page($resource, 'edit_option', __('Setting'), $settings)
            ->mapActions(['GET' => 'edit_option', 'PUT' => 'update_option']);

        $index = tr_page($resource, 'index', __('List Domain'), $settings);

        foreach ([$add, $delete, $edit, $edit_option, $index] as $page) {
            /** @var \TypeRocket\Register\Page $page */
            $page->useController()->addNewButton()->removeMenu();
        }

        $index->apply($add, $delete, $edit, $edit_option)->addNewButton();


    }

    public function add_crawl_category_menu()
    {
        $resource = 'crawl_category';

        $settings = [
            'capability' => 'administrator',
            'position'   => 999,
        ];

        $add = tr_page($resource, 'add', __('Add Category'), $settings)
            ->mapActions(['GET' => 'add', 'POST' => 'create']);

        $delete = tr_page($resource, 'delete', __('Delete Category'), $settings)
            ->mapActions(['GET' => 'delete', 'DELETE' => 'destroy']);

        $edit = tr_page($resource, 'edit', __('Edit Category'), $settings)
            ->mapActions(['GET' => 'edit', 'PUT' => 'update']);

        $edit_option = tr_page($resource, 'edit_option', __('Setting'), $settings)
            ->mapActions(['GET' => 'edit_option', 'PUT' => 'update_option']);

        $index = tr_page($resource, 'index', __('List Category'), $settings);

        foreach ([$add, $delete, $edit, $edit_option, $index] as $page) {
            /** @var \TypeRocket\Register\Page $page */
            $page->useController()->addNewButton()->removeMenu();
        }

        $this->crawl_category = $index->apply($add, $delete, $edit, $edit_option)->addNewButton();
    }

    public function customize_menu_labels()
    {
        global $menu;
        global $submenu;
    }

}

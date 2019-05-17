<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/phuchnh
 * @since      1.0.0
 *
 * @package    Web_Crawler
 * @subpackage Web_Crawler/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Web_Crawler
 * @subpackage Web_Crawler/includes
 * @author     phuchnh <huynhngochoangphuc@gmail.com>
 */
class Web_Crawler_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        static::create_table_crawl_domains();
        static::create_table_crawl_categories();
        static::create_table_crawl_settings();
        static::create_table_crawl_links();
        static::activate_crawl_schedule_event();
    }

    private static function activate_crawl_schedule_event()
    {
        if ( ! wp_next_scheduled('crawl_schedule_event')) {
            wp_schedule_event(time(), 'five_minutes', 'crawl_schedule_event');
        }
    }

    private static function create_table_crawl_links()
    {
        global $wpdb;
        $charset_collate  = $wpdb->get_charset_collate();
        $crawl_link_table = $wpdb->prefix . 'crawl_links';

        $sql = "CREATE TABLE IF NOT EXISTS {$crawl_link_table} (
              id         int(11) UNSIGNED                   NOT NULL AUTO_INCREMENT,
              link       text                               NOT NULL,
              status     boolean  DEFAULT FALSE,
              options    longtext,
              created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
              updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (id)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    private static function create_table_crawl_settings()
    {
        global $wpdb;
        $charset_collate     = $wpdb->get_charset_collate();
        $crawl_setting_table = $wpdb->prefix . 'crawl_settings';
        $crawl_domains_table = $wpdb->prefix . 'crawl_domains';

        $sql = "CREATE TABLE IF NOT EXISTS {$crawl_setting_table} (
              id               int(11) UNSIGNED                   NOT NULL AUTO_INCREMENT,
              crawl_domain_id  int(11) UNSIGNED                   NOT NULL,
              categories       longtext,
              options          longtext,
              status           boolean  DEFAULT FALSE,
              created_at       datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
              updated_at       datetime DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (id),
              FOREIGN KEY (crawl_domain_id) REFERENCES {$crawl_domains_table} (id) ON DELETE CASCADE
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    private static function create_table_crawl_categories()
    {
        global $wpdb;
        $charset_collate       = $wpdb->get_charset_collate();
        $crawl_categorie_table = $wpdb->prefix . 'crawl_categories';
        $crawl_domain_table    = $wpdb->prefix . 'crawl_domains';

        $sql = "CREATE TABLE IF NOT EXISTS {$crawl_categorie_table} (
            id               int(11) UNSIGNED                   NOT NULL AUTO_INCREMENT,
		    crawl_domain_id  int(11) UNSIGNED                   NOT NULL,
		    category_url     VARCHAR(191)                       NOT NULL,
		    created_at       datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		    updated_at       datetime DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
		    PRIMARY KEY (id),
		    CONSTRAINT unique_category_url UNIQUE (category_url),
		    FOREIGN KEY (crawl_domain_id) REFERENCES {$crawl_domain_table} (id) ON DELETE CASCADE
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    private static function create_table_crawl_domains()
    {
        global $wpdb;
        $charset_collate    = $wpdb->get_charset_collate();
        $crawl_domain_table = $wpdb->prefix . 'crawl_domains';
        $sql                = "CREATE TABLE IF NOT EXISTS {$crawl_domain_table} (
            id                  int(11) UNSIGNED                   NOT NULL AUTO_INCREMENT,
		    domain_url          VARCHAR(191)                       NOT NULL,
		    archive_options     longtext,
		    single_options      longtext,
		    created_at          datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		    updated_at          datetime DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
		    PRIMARY KEY (id),
		    CONSTRAINT unique_domain_url UNIQUE (domain_url)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

}

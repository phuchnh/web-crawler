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
class Web_Crawler_Activator {
	
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		static::create_table_crawl_domains();
		static::create_table_crawl_categories();
	}
	
	private static function create_table_crawl_categories() {
		global $wpdb;
		$charset_collate  = $wpdb->get_charset_collate();
		$crawl_categories = $wpdb->prefix . 'crawl_categories';
		$crawl_domains    = $wpdb->prefix . 'crawl_domains';
		
		$sql = "CREATE TABLE IF NOT EXISTS {$crawl_categories} (
            id               int(11) UNSIGNED                   NOT NULL AUTO_INCREMENT,
		    crawl_domain_id  int(11) UNSIGNED                   NOT NULL,
		    category_url     VARCHAR(191)                       NOT NULL,
		    category_options longtext,
		    created_at       datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		    updated_at       datetime DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
		    PRIMARY KEY (id),
		    CONSTRAINT unique_category_url UNIQUE (category_url),
		    FOREIGN KEY (crawl_domain_id) REFERENCES {$crawl_domains} (id) ON DELETE CASCADE
        ) {$charset_collate};";
		
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
	
	private static function create_table_crawl_domains() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$crawl_domains   = $wpdb->prefix . 'crawl_domains';
		$sql             = "CREATE TABLE IF NOT EXISTS {$crawl_domains} (
            id             int(11) UNSIGNED                   NOT NULL AUTO_INCREMENT,
		    domain_url     VARCHAR(191)                       NOT NULL,
		    domain_options longtext,
		    created_at     datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		    updated_at     datetime DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
		    PRIMARY KEY (id),
		    CONSTRAINT unique_domain_url UNIQUE (domain_url)
        ) {$charset_collate};";
		
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
	
}

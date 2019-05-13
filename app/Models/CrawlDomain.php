<?php

namespace App\Models;

use \TypeRocket\Models\Model;

class CrawlDomain extends Model {
	/**
	 * @var string
	 */
	protected $resource = 'crawl_domains';
	
	/**
	 * @var string
	 */
	protected $table = 'wp_crawl_domains';
	
	/**
	 * @var array
	 */
	protected $fillable = [
		'domain_url',
		'domain_options',
	];
	
	/**
	 * @var array
	 */
	protected $cast = [
		'domain_url'     => 'string',
		'domain_options' => 'array',
	];
	
	/**
	 * @return \TypeRocket\Models\Model|null
	 */
	public function categories() {
		return $this->hasMany( CrawlCategory::class, 'crawl_domain_id' );
	}
	
	/**
	 * @return array
	 * @throws \Exception
	 */
	public static function get_all_crawl_domain() {
		$crawl_domains = new CrawlDomain();
		$crawl_domains = $crawl_domains->findAll()->get();
		
		$options = [];
		foreach ( $crawl_domains as $item ) {
			$options[ $item->domain_url ] = $item->id;
		}
		
		return $options;
	}
}
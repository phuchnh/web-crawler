<?php

namespace App\Models;

use \TypeRocket\Models\Model;

class CrawlSetting extends Model
{
    /**
     * @var string
     */
    protected $resource = 'crawl_settings';

    /**
     * @var string
     */
    protected $table = 'wp_crawl_settings';

    /**
     * @var array
     */
    protected $fillable = [
        'crawl_domain_id',
        'categories',
        'options',
    ];

    /**
     * @var array
     */
    protected $cast = [
        'crawl_domain_id' => 'string',
        'categories'      => 'array',
        'options'         => 'array',
    ];

    /**
     * @return CrawlSetting|null
     */
    public function domain()
    {
        return $this->belongsTo(CrawlDomain::class, 'crawl_domain_id');
    }
}
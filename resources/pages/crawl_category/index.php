<?php

$table = tr_tables();
$table->setColumns('category_url', [
    'category_url'    => [
        'sort'    => true,
        'label'   => 'Category URL',
        'actions' => ['edit', 'delete'],
    ],
    'crawl_domain_id' => [
        'sort'     => true,
        'label'    => 'Domain URL',
        'callback' => function ($id, \App\Models\CrawlCategory $result) {
            if ($domain = $result->domain) {
                $url = tr_redirect()->toPage('crawl_domain', 'edit', $id)->url;
                
                return (new \TypeRocket\Html\Generator())->newLink($domain->domain_url, $url);
            }
            
            return '';
        },
    ],
]);

try {
    $table->render('test');
} catch (Exception $e) {
    exit(500);
}

<?php

$table = tr_tables();
$table->setColumns('domain_url', [
    'domain_url' => [
        'sort'    => true,
        'label'   => 'Domain URL',
        'actions' => ['edit', 'delete'],
    ],
    'id'         => [
        'label'    => 'Setting',
        'callback' => function ($id, \App\Models\CrawlDomain $result) {
            if ($result) {
                $route   = [
                    'archive' => tr_redirect()->toPage('crawl_domain', 'archive', $id)->url,
                    'single'  => tr_redirect()->toPage('crawl_domain', 'single', $id)->url,
                ];
                $archive = (new \TypeRocket\Html\Generator())->newLink('Archive', $route['archive']);
                $single  = (new \TypeRocket\Html\Generator())->newLink('Single', $route['single']);
                
                return $single.' | '.$archive;
            }
            
            return '';
        },
    ],
]);

try {
    $table->render();
} catch (Exception $e) {
    exit(500);
}

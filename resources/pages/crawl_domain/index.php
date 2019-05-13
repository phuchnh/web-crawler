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
                $url = tr_redirect()->toPage('crawl_domain', 'edit_option', $id)->url;
			
                return '<a href="' . $url . '">' . 'Edit' . '</a>';
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

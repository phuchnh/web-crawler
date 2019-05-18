<?php /** @noinspection PhpUnhandledExceptionInspection */

$table = tr_tables();
$table->setColumns('crawl_domain_id', [
    'crawl_domain_id' => [
        'sort'     => true,
        'label'    => 'Domain URL',
        'actions' => ['edit', 'delete'],
        'callback' => function ($id, \App\Models\CrawlSetting $result) {
            if ($domain = $result->domain) {
                $url = tr_redirect()->toPage('crawl_setting', 'edit', $result->id)->url;

                return (new \TypeRocket\Html\Generator())->newLink($domain->domain_url, $url);
            }

            return '';
        },
    ],
]);

$table->render('test');
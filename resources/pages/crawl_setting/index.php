<?php /** @noinspection PhpUnhandledExceptionInspection */

$table = tr_tables();
$table->setColumns('id', [
    'id'    => [
        'sort'    => true,
        'label'   => 'ID',
        'actions' => ['edit', 'delete'],
    ],
    'crawl_domain_id' => [
        'sort'     => true,
        'label'    => 'Domain URL',
        'callback' => function ($id, \App\Models\CrawlSetting $result) {
            if ($domain = $result->domain) {
                $url = tr_redirect()->toPage('crawl_domain', 'edit', $id)->url;

                return (new \TypeRocket\Html\Generator())->newLink($domain->domain_url, $url);
            }

            return '';
        },
    ],
]);

$table->render('test');
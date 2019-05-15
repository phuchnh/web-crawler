<?php /** @noinspection PhpUnhandledExceptionInspection */
/**
 * @var $form \TypeRocket\Elements\Form
 */

$domains = $form->select('crawl_domain_id')->setLabel('Domain URL');
$domains->setModelOptions(new \App\Models\CrawlDomain, 'domain_url', 'id');
$domains->setAttribute('class', 'select2');

// Render form
$form->useOld();
echo $form->open();
echo $domains;
echo $form->items('categories')->setLabel('Categories');
echo $form->submit('Add');
echo $form->close();
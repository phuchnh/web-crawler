<?php /** @noinspection PhpUnhandledExceptionInspection */
/**
 * @var $form \TypeRocket\Elements\Form
 */

$domains = $form->select( 'crawl_domain_id' )->setLabel( 'Domain URL' );
$domains->setModelOptions( new \App\Models\CrawlDomain, 'domain_url', 'id' );
$domains->setAttributes( [ 'id' => 'crawl_domain', 'class' => 'crawl_domain' ] );

// Render form
$form->useOld();
echo $form->open();
echo $domains;
echo $form->text( 'category_url' )->setLabel( 'Category URL' );
echo $form->submit( 'Add' );
echo $form->close();

?>

<script lang="js">
  (function($) {
    'use strict';
    $(function() {
      var $crawl_domain = $('#crawl_domain');
      $crawl_domain.select2({ placeholder: 'Select an option' });
    });
  })(jQuery);
</script>

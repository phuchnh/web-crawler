<?php /** @noinspection PhpUnhandledExceptionInspection */

/**@var $form \TypeRocket\Elements\Form */

$form->useJson();
$form->useUrl('POST', 'crawler/preview');

$domains = $form->select('domain_id')->setLabel('Domain URL');
$domains->setModelOptions(new \App\Models\CrawlDomain, 'domain_url', 'id');
$domains->setAttribute('class', 'select2');

$catogories = $form->select('category_id')->setLabel('Category URL');
$catogories->setModelOptions(new \App\Models\CrawlCategory, 'category_url', 'id');
$catogories->setAttribute('class', 'select2');

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">

<div class="flex md:flex-row flex-wrap">
  <div class="w-full md:w-2/5 p-4">
      <?php echo $form->open() ?>
    <fieldset>
      <div class="mb-4">
          <?php echo $domains; ?>
      </div>
      <div class="mb-4">
          <?php echo $catogories; ?>
      </div>
      <div class="mb-4">
          <?php echo $form->submit('Preview'); ?>
      </div>
    </fieldset>
      <?php echo $form->close() ?>
  </div>
  <div class="w-full md:w-3/5 p-4">
    <div id="review_result">
      qeqeqwe
    </div>
  </div>
</div>

<script>
  (function($) {
    'use strict';
    $(function() {
      TypeRocket.httpCallbacks.push(function(response) {
        console.log(response);
      });
    });
  })(jQuery);
</script>
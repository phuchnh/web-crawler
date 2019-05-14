<?php /** @noinspection PhpUnhandledExceptionInspection */

/**@var $form \TypeRocket\Elements\Form */

$form->useUrl('POST', 'crawler/preview')->useAjax();

//
$data    = [];
$options = (new \App\Models\CrawlCategory)->findAll()->get();
foreach ($options as $option) {
    $item['id']   = $option->id;
    $item['text'] = $option->category_url;
}

$domains = $form->select('domain_id')->setLabel('Domain URL');
$domains->setModelOptions(new \App\Models\CrawlDomain, 'domain_url', 'id');
$domains->setAttribute('class', 'select2');
$domains->setAttribute('id', 'domain');

$catogories = $form->select('category_id')->setLabel('Category URL');
$catogories->setModelOptions(new \App\Models\CrawlCategory, 'category_url', 'id');
$catogories->setAttribute('class', 'select2');
$catogories->setAttribute('id', 'category');

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
          <?php echo $form->submit('Preview')->setAttribute('id', 'preview_btn'); ?>
      </div>
    </fieldset>
      <?php echo $form->close() ?>
  </div>
  <div class="w-full md:w-3/5 p-4">
    <div id="preview_show"></div>
  </div>
</div>

<script>
  (function($) {
    'use strict';
    $(function() {
      var $result = $('#preview_show');
      var $category = $('#category');
      var $domain = $('#domain');

      $domain.change(function(e) {
        e.preventDefault();
        var value = $(this).val();
      });

      $('#preview_btn').click(function() {
        $result.empty();
        $.LoadingOverlay('show', { maxSize: 50 });
      });

      TypeRocket.httpCallbacks.push(function(response) {

        if (response.status !== 200) {
          $.LoadingOverlay('hide');
          return;
        }

        var resource = _.get(response, 'data.resource', []);

        var items = [];

        _.each(resource, function(obj) {

          var div = document.createElement('div');
          div.className = 'mb-4';
          div.classList.add('bg-white', 'shadow-md', 'p-4');

          var block = document.createElement('div');
          block.className = 'control-group';

          var keys = _.keys(obj) || [];

          _.each(keys, function(key) {

            var label = document.createElement('p');
            label.className = 'font-bold';
            label.innerHTML = key;

            var content = null;
            var type = obj[key]['type'];
            var value = obj[key]['value'] || '';

            if (type === 'link') {
              content = document.createElement('a');
              content.href = value;
              content.target = 'blank';
              content.innerHTML = value;
            }

            if (type === 'image') {
              content = document.createElement('img');
              content.src = value;
              content.classList.add('object-cover', 'w-1/4', 'h-1/4');
            }

            if (['text', 'html'].indexOf(type) > -1) {
              content = document.createElement('p');
              content.innerHTML = value;
            }

            // Append content to item
            $(block).append(label, content);
          });

          // Append item to list
          items.push($(div).append(block));
        });

        $result.append(_.assign([], items));
        $.LoadingOverlay('hide');
      });
    });
  })(jQuery);
</script>
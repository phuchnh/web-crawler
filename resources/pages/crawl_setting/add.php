<?php /** @noinspection PhpUnhandledExceptionInspection */
/**
 * @var $form \TypeRocket\Elements\Form
 */
$form->useOld();

$domains = $form->select('crawl_domain_id')->setLabel('Domain URL');
$domains->setModelOptions(new \App\Models\CrawlDomain, 'domain_url', 'id');
$domains->setAttribute('id', 'domain');
$domains->setAttribute('class', 'select2');

$select = $form->select('category_id');
$select->setAttribute('class', 'select2');
$select->setLabel('Category URL');

$options = array_map(function (\App\Models\CrawlCategory $value) {
    return [
        'id'        => $value->id,
        'text'      => $value->category_url,
        'domain_id' => $value->crawl_domain_id,
    ];
}, (array)(new \App\Models\CrawlCategory)->findAll()->get() ?? []);

$repeater = $form->repeater('categories');
$repeater->setFields([$select]);
$repeater->setLabel('Categories');

// Render form
echo $form->open();
echo $domains;
echo $repeater;
echo $form->submit('Add');
echo $form->close();

?>

<script>
  (function($) {
    'use strict';
    $(function() {
      var categories = <?php echo json_encode($options);?>;
      var $domain = $('#domain');

      function getCategoriesBelongsToDomain(domainId, $dom) {
        // Empty options
        $dom.empty();

        // Get categories belongs to domain
        var options = _.reduce(categories, function(result, value) {
          if (+value['domain_id'] === +domainId) {
            result.push(new Option(value['text'], value['id']));
          }
          return result;
        }, []);

        // Append new options
        $dom.append(options);
      }

      TypeRocket.repeaterCallbacks.push(function($template) {
        var $select = $template.find('select').select2({ width: '100%' });

        // Remove unused element after re-init select2
        $select.last().next().next().remove();
        
        getCategoriesBelongsToDomain($domain.val(), $select);
      });

      $domain.change(function(e) {
        e.preventDefault();
        $('.tr-repeater-fields').empty();
      });
    });
  })(jQuery);
</script>

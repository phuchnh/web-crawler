<?php

/**
 * @var $form \TypeRocket\Elements\Form
 */

$select = $form->select('type');
$select->setOptions(['Text' => 'text', 'Image' => 'image', 'HTML' => 'html']);
$select->setLabel('Type');
$select->setAttribute('class', 'select2');

$repeater = $form->repeater('domain_options');
$repeater->setFields([
    $form->row($form->text('Title'), $select),
    $form->text('Selector'),
]);

$repeater->setLabel('Options');

echo $form->open();
echo $form->text('domain_url')->setLabel('Domain URL')->setAttribute('disabled', 'disabled');
echo $repeater;
echo $form->submit('Update');
echo $form->close();

?>

<script language="JavaScript">
  (function($) {
    'use strict';
    $(function() {
      TypeRocket.repeaterCallbacks.push(function($template) {
        var $select = $template.find('select').select2({width: '100%'});

        // Remove unused element after re-init select2
        // file ./wp-content/plugins/web-crawler/admin/js/web-crawler-admin.js
        $select.last().next().next().remove();
      });
    });
  })(jQuery);
</script>

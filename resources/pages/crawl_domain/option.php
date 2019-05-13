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

$repeater->setLabel('Selectors');

echo $form->open();
echo $repeater;
echo $form->submit('Update');
echo $form->close();
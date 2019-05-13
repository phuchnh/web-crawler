<?php

/**
 * @var $form \TypeRocket\Elements\Form
 */

$select = $form->select('type')->setOptions([
    'Text'  => 'text',
    'Image' => 'image',
    'HTML'  => 'html',
]);

$select->setLabel('Type');

$repeater = $form->repeater('domain_options')->setFields([
    $form->text('Selector'),
    $form->row($form->text('Title'), $select),
]);

$repeater->setLabel('Selectors');

echo $form->open();
echo $repeater;
echo $form->submit('Update');
echo $form->close();
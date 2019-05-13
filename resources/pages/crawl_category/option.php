<?php

/**
 * @var $form \TypeRocket\Elements\Form
 */

echo $form->open();
echo $form->text('category_url')->setLabel('Category URL')->setAttribute('disabled', 'disabled');
$form->setGroup('category_options');
echo $form->text('selector')->setLabel('Selector');
echo $form->text('pagination')->setLabel('Pagination');
echo $form->submit('Update');
echo $form->close();

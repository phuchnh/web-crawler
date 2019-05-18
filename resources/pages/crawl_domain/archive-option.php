<?php

/**
 * @var $form \TypeRocket\Elements\Form
 */
$form->useOld();
echo $form->open();
echo $form->text('domain_url')->setLabel('Domain URL')->setAttribute('readonly', 'readonly');
$form->setGroup('archive_options');
echo $form->text('selector')->setLabel('Selector');
echo $form->text('pagination')->setLabel('Pagination');
echo $form->submit('Update');
echo $form->close();

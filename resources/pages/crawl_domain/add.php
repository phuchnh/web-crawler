<?php

/**
 * @var $form \TypeRocket\Elements\Form
 */
$form->useOld();
echo $form->open();
echo $form->text( 'domain_url' )->setLabel( 'Domain URL' );
echo $form->submit( 'Add' );
echo $form->close();

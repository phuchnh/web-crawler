<?php

/**
 * @var $form \TypeRocket\Elements\Form
 */

$select = $form->select( 'Type' )->setOptions( [
	'Text'  => 'text',
	'Image' => 'image',
	'HTML'  => 'html',
] );



$box = tr_meta_box('Speakers');
$box->addScreen( 'event' );
$box->setCallback(function() {
	$form = tr_form();
	$repeater = $form->repeater('Speakers')->setFields([
		$form->image('Photo'),
		$form->text('Name'),
		$form->text('Slides URL')
	]);
	
	echo $repeater;
});

echo $form->open();
echo $box;
echo $form->submit( 'Update' );
echo $form->close();
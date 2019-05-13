<?php

$table = tr_tables();
$table->setColumns( 'domain_url', [
	'domain_url' => [
		'sort'    => true,
		'label'   => 'Domain URL',
		'actions' => [ 'edit', 'delete' ],
	],
] );

try {
	$table->render();
} catch ( Exception $e ) {
	exit( 500 );
}

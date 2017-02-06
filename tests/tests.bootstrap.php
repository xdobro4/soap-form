<?php

if (isset($_ENV['BOOTSTRAP_CLEAR_DB'])) {
	passthru(sprintf(
		'php "%s/../bin/console" doctrine:database:drop --force -e test', __DIR__
	));

	passthru(sprintf(
		'php "%s/../bin/console" doctrine:database:create -e test', __DIR__
	));

	passthru(sprintf(
		'php "%s/../bin/console" doctrine:schema:update --force -e test', __DIR__
	));
}

require __DIR__ . '/../app/autoload.php';

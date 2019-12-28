<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
//$dotenv->load();

// Hack for PHP Built-in Development server!!!
if (php_sapi_name() === 'cli-server') {
	$_SERVER['SCRIPT_NAME'] = '/index.php';
}

App\Bootstrap::boot()
	->createContainer()
	->getByType(Contributte\Middlewares\Application\IApplication::class)
	->run();

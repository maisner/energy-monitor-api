<?php

declare(strict_types = 1);

namespace App;

use Nette\Configurator;


class Bootstrap {
	public static function boot(): Configurator {
		$configurator = new Configurator;

		//$configurator->setDebugMode('23.75.345.200'); // enable for your remote IP
		$configurator->setDebugMode(TRUE);
		$configurator->enableTracy(__DIR__ . '/../log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory(__DIR__ . '/../temp');

		$configurator
			->addConfig(__DIR__ . '/config/apitte.neon')
			->addConfig(__DIR__ . '/config/common.neon')
			->addConfig(__DIR__ . '/config/config.php');

		return $configurator;
	}


	public static function bootForTests(): Configurator {
		$configurator = self::boot();
		\Tester\Environment::setup();

		return $configurator;
	}
}

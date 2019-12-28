<?php

return [
	'parameters' => [
		'db' => [
			'dsn'  => \sprintf('mysql:host=%s;dbname=%s', getenv('MYSQL_HOST'), getenv('MYSQL_NAME')),
			'user' => getenv('MYSQL_USER'),
			'pass' => getenv('MYSQL_PASS'),
		]
	]
];

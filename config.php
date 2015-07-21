<?php

Define('APP_FILE_PATH', '/slash/app.php');

$config = [
	'app.environment' => \Slash\Slash::DEV,
	'app.debug' => true,
	'route.caseSensitive' => false,
	'template.path' => __DIR__."/app/views"
];

return $config;
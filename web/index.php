<?php

namespace ICanBoogie;

if (php_sapi_name() === 'cli-server')
{
	$uri = urldecode(
		parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
	);

	if ($uri !== '/' && file_exists(__DIR__ . $uri))
	{
		return false;
	}

	unset($uri);
}

(function(Core $app) {

	$app();

}) (require __DIR__ . '/../bootstrap.php');

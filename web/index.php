<?php

namespace App;

/* @var $app Application */

if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . $_SERVER["REQUEST_URI"]))
{
	return false;
}

//$log = json_encode([ $_SERVER['REQUEST_TIME_FLOAT'], $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI']]);
//file_put_contents(__DIR__ . '/../access.log', $log . PHP_EOL, FILE_APPEND);

$app = require __DIR__ . '/../bootstrap.php';
$app();

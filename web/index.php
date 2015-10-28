<?php

namespace App;

/* @var $app Application */

if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . $_SERVER["REQUEST_URI"]))
{
	return false;
}

$app = require __DIR__ . '/../bootstrap.php';
$app();

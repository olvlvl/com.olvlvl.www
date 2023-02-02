<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie;

use Throwable;

chdir(dirname(__DIR__));

/*
 * URL rewriting functionality for the built-in PHP web server.
 */
if (PHP_SAPI === 'cli-server')
{
	$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

	if ($uri !== '/' && file_exists(__DIR__ . $uri))
	{
		return false;
	}

	unset($uri);
}

/**
 * Obtain the booted application and run it.
 *
 * @var $app Application
 */
$app = require __DIR__ . '/../bootstrap.php';

try {
	$app();
} catch (Throwable $e) {

	if (EventProfiler::$unused) {
		echo '<pre>';
		echo "# Unused events\n";

		foreach (EventProfiler::$unused as [ $time, $event ]) {
			echo "[$time] $event\n";
		}
		echo '</pre>';
	}

	echo '<pre>' . $e . '</pre>';
}

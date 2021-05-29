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

define('App\PAGES', __DIR__ . '/content/pages');

if (file_exists(__DIR__ . '/vendor/icanboogie-combined.php'))
{
	require __DIR__ . '/vendor/icanboogie-combined.php';
}

require __DIR__ . '/vendor/autoload.php';

return boot();

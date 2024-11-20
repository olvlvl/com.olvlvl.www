<?php

namespace App\Modules\Cache\Listener;

use DirectoryIterator;
use ICanBoogie\Application\ClearCacheEvent;
use ICanBoogie\Event\Listener;
use RegexIterator;

use function file_exists;
use function getcwd;
use function unlink;

final class ClearCacheListener
{
	#[Listener]
	public static function on(ClearCacheEvent $event): void
	{
		$cwd = getcwd();

		if ($cwd === false || !file_exists("$cwd/web")) {
			return;
		}

		$iterator = new RegexIterator(new DirectoryIterator("$cwd/web"), '/.*\.html$/');

		foreach ($iterator as $file) {
			/** @var DirectoryIterator $file */

			unlink($file->getPathname());

			$event->cleared("rendered routes: $file");
		}
	}
}

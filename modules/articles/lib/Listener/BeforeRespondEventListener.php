<?php

namespace App\Modules\Articles\Listener;

use App\Modules\Articles\ArticleSynchronizer;
use ICanBoogie\HTTP\Responder\WithEvent\BeforeRespondEvent;
use ICanBoogie\Module\ModuleInstaller;

use ICanBoogie\Module\ModuleInstaller\ModuleInstallFailed;

use function file_exists;
use function filemtime;

final class BeforeRespondEventListener
{
	/**
	 * @param string[] $article_locations
	 */
	public function __construct(
		private readonly array $article_locations,
		private readonly string $database_location,
		private readonly ArticleSynchronizer $synchronizer,
		private readonly ModuleInstaller $module_installer,
	) {
	}

	/**
	 * @throws ModuleInstallFailed
	 */
	public function __invoke(BeforeRespondEvent $event): void
	{
		if (!$this->should_update( $database_exists)) {
			return;
		}

		if (!$database_exists) {
			$this->module_installer->install_all();
		}

		$this->synchronizer->synchronize();
	}

	private function should_update(&$database_exists): bool
	{
		$database_exists = file_exists($this->database_location);

		if (!$database_exists) {
			return true;
		}

		$database_mtime = filemtime($this->database_location);

		foreach ($this->article_locations as $directory) {
			if ($database_mtime < filemtime($directory)) {
				return true;
			}
		}

		return false;
	}
}

<?php

namespace App\Modules\Articles\Listener;

use App\Modules\Articles\ArticleController;
use App\Modules\Articles\ArticleSynchronizer;
use ICanBoogie\Module\ModuleCollectionInstallFailed;
use ICanBoogie\Routing\Controller\BeforeActionEvent;

use function file_exists;
use function filemtime;

final class BeforeControllerActionListener
{
	/**
	 * @param string[] $article_locations
	 */
	public function __construct(
		private readonly array $article_locations,
		private readonly string $database_location,
		private readonly ArticleSynchronizer $synchronizer
	) {
	}

	/**
	 * @throws ModuleCollectionInstallFailed
	 */
	public function __invoke(BeforeActionEvent $event, ArticleController $controller): void
	{
		if (!$this->should_update( $database_exists)) {
			return;
		}

		if (!$database_exists) {
			$controller->app->modules->install();
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

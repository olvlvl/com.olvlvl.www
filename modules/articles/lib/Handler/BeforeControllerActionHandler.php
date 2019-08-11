<?php

namespace App\Modules\Articles\Handler;

use App\Modules\Articles\ArticleController;
use ICanBoogie\Module\ModuleCollectionInstallFailed;
use ICanBoogie\Routing\Controller\BeforeActionEvent;
use function file_exists;
use function filemtime;

final class BeforeControllerActionHandler
{
	/**
	 * @var string[]
	 */
	private $articles_location;

	/**
	 * @var string
	 */
	private $database_location;

	/**
	 * @param string[] $articles_location
	 */
	public function __construct(array $articles_location, string $database_location)
	{
		$this->articles_location = $articles_location;
		$this->database_location = $database_location;
	}

	/**
	 * @throws ModuleCollectionInstallFailed
	 */
	public function __invoke(BeforeActionEvent $event, ArticleController $controller): void
	{
		$directories = $this->articles_location;

		if (!$this->shouldUpdate($directories, $database_exists))
		{
			return;
		}

		if (!$database_exists)
		{
			$controller->app->modules->install();
		}

		$module = $controller->module;
		$module->sync($directories);
	}

	private function shouldUpdate(array $directories, &$exists): bool
	{
		$exists = file_exists($this->database_location);

		if (!$exists)
		{
			return true;
		}

		$database_mtime = filemtime($this->database_location);

		foreach ($directories as $directory)
		{
			if ($database_mtime < filemtime($directory))
			{
				return true;
			}
		}

		return false;
	}
}

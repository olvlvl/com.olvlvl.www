<?php

namespace App\Modules\Articles;

/**
 * @property-read ArticleModel $model
 */
class Module extends \ICanBoogie\Module
{
	/**
	 * Synchronises articles.
	 */
	public function sync()
	{
		$di = new \DirectoryIterator(\App\ROOT . '_articles');
		$importer = new ArticleImporter($this->model);

		foreach ($di as $file)
		{
			if (!$file->isFile())
			{
				continue;
			}

			$importer($file);
		}
	}
}

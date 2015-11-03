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
		$ids = [];

		foreach ($di as $file)
		{
			if (!$file->isFile())
			{
				continue;
			}

			$article = $importer($file);
			$ids[] = $article->article_id;
		}

		$this->model->where([ '!article_id' => $ids ])->delete();
	}
}

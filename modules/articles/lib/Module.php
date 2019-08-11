<?php

namespace App\Modules\Articles;

/**
 * @property-read ArticleModel $model
 */
class Module extends \ICanBoogie\Module
{
	/**
	 * Synchronises articles.
	 *
	 * @param string[] $directories
	 */
	public function sync(array $directories)
	{
		$synchronizer = new ArticleSynchronizer($this->model);
		$synchronizer($directories);
	}
}

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
		$synchronizer = new ArticleSynchronizer($this->model);
		$synchronizer(\App\ROOT . '_articles');
	}
}

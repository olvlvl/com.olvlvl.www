<?php

namespace App\Modules\Articles;

class ArticlesSynchronizer
{
	/**
	 * @var ArticleModel
	 */
	private $model;

	/**
	 * @var ArticleImporter
	 */
	private $importer;

	/**
	 * @param ArticleModel $model
	 */
	public function __construct(ArticleModel $model)
	{
		$this->model = $model;
		$this->importer = new ArticleImporter($model);
	}

	/**
	 * @param string $directory Where the articles are.
	 */
	public function __invoke($directory)
	{
		$di = new \DirectoryIterator($directory);
		$importer = $this->importer;
		$ids = [];

		foreach ($di as $file)
		{
			if (!$file->isFile())
			{
				continue;
			}

			$ids[] = $importer($file)->article_id;
		}

		$this->model->where([ '!article_id' => $ids ])->delete();
	}
}

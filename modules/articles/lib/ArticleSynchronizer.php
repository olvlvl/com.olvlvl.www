<?php

namespace App\Modules\Articles;

class ArticleSynchronizer
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
	 * @param array $directories
	 *
	 * @internal param string $directory Where the articles are.
	 */
	public function __invoke(array $directories)
	{
		$ids = [];

		foreach ($directories as $directory) {
			$ids = array_merge($ids, $this->importArticles($directory));
		}

		$this->model->where([ '!article_id' => $ids ])->delete();
	}

	/**
	 * @param string $directory
	 *
	 * @return array
	 */
	private function importArticles($directory)
	{
		$di = new \DirectoryIterator($directory);
		$di = new \RegexIterator($di, '/\.md$/');
		$importer = $this->importer;
		$ids = [];

		foreach ($di as $file)
		{
			if (!$file->isFile())
			{
				continue;
			}

			try
			{
				$ids[] = $importer($file)->article_id;
			}
			catch (\Throwable $e)
			{
				throw new \RuntimeException("Unable to import article `$file`.", 0, $e);
			}
		}

		return $ids;
	}
}

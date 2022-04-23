<?php

namespace App\Modules\Articles;

use DirectoryIterator;
use RegexIterator;
use RuntimeException;
use Throwable;

final class ArticleSynchronizer
{
	private ArticleImporter $importer;

	public function __construct(
		private readonly ArticleModel $model
	) {
		$this->importer = new ArticleImporter($model);
	}

	/**
	 * @param string[] $directories
	 */
	public function __invoke(array $directories): void
	{
		$ids = [];

		foreach ($directories as $directory) {
			$ids = array_merge($ids, $this->importArticles($directory));
		}

		$this->model->where([ '!article_id' => $ids ])->delete();
	}

	/**
	 * @return int[]
	 */
	private function importArticles(string $directory): array
	{
		$di = new DirectoryIterator($directory);
		$di = new RegexIterator($di, '/\.md$/');
		$importer = $this->importer;
		$ids = [];

		foreach ($di as $file) {
			if (!$file->isFile()) {
				continue;
			}

			try {
				$ids[] = $importer($file)->article_id;
			} catch (Throwable $e) {
				throw new RuntimeException("Unable to import article `$file`.", 0, $e);
			}
		}

		return $ids;
	}
}

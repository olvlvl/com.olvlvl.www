<?php

namespace App\Modules\Articles;

use DirectoryIterator;
use RegexIterator;
use RuntimeException;
use Throwable;

final class ArticleSynchronizerWithImporter implements ArticleSynchronizer
{
	private ArticleImporter $importer;

	/**
	 * @param string[] $article_locations
	 */
	public function __construct(
		private readonly ArticleModel $model,
		private readonly array $article_locations,
	) {
		$this->importer = new ArticleImporter($model);
	}

	public function synchronize(): void
	{
		$ids = [];

		foreach ($this->article_locations as $directory) {
			$ids = array_merge($ids, $this->import_articles($directory));
		}

		$this->model->where([ '!article_id' => $ids ])->delete();
	}

	/**
	 * @return int[]
	 *     The identifiers of the imported articles.
	 */
	private function import_articles(string $directory): array
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

<?php

namespace App\Modules\Articles;

use DirectoryIterator;
use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\Binding\ActiveRecord\Record;
use RegexIterator;
use RuntimeException;
use Throwable;

final class ArticleSynchronizerWithImporter implements ArticleSynchronizer
{
	/**
	 * @param string[] $article_locations
	 */
	public function __construct(
		#[Record(Article::class)]
		private readonly Model $model,
		private readonly ArticleImporter $importer,
		private readonly array $article_locations,
	) {
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
				throw new RuntimeException("Unable to import article `$file`.", previous: $e);
			}
		}

		return $ids;
	}
}

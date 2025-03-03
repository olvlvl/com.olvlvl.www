<?php

namespace App\Modules\Articles;

use DirectoryIterator;
use RegexIterator;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Throwable;

final readonly class ArticleSynchronizerWithImporter implements ArticleSynchronizer
{
	public function __construct(
		private ArticleImporter $importer,
        #[Autowire(param: 'article_location')]
		private string $article_location,
	) {
	}

	public function synchronize(): void
	{
        $ids = $this->import_articles($this->article_location);

		Article::where([ '!article_id' => $ids ])->delete();
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

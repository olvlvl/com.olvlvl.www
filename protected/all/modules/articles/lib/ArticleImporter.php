<?php

namespace App\Modules\Articles;

use ICanBoogie\DateTime;

class ArticleImporter
{
	static private function hash(\SplFileInfo $file)
	{
		return base64_encode(hash_file('sha384', $file->getPathname(), 'true'));
	}

	/**
	 * @var ArticleModel
	 */
	private $model;

	public function __construct(ArticleModel $model)
	{
		$this->model = $model;
		$this->markdown = new \Parsedown();
	}

	public function __invoke(\SplFileInfo $file)
	{
		$filename = $file->getFilename();
		$date_string = substr($filename, 0, 8);
		$date = DateTime::from($date_string . '120000', 'Europe/Berlin');
		$slug = basename(substr($filename, 9), '.md');
		$hash = self::hash($file);

		$article = $this->model->where('date = ? AND slug = ?', $date, $slug)->one;

		if ($article)
		{
			if ($article->hash === $hash)
			{
				return;
			}
		}
		else
		{
			$article = Article::from([

				'slug' => $slug,
				'date' => $date

			]);
		}

		list($title, $body) = $this->markdown($file);

		$article->title = $title;
		$article->body = $body;
		$article->excerpt = \ICanBoogie\excerpt($body);
		$article->hash = $hash;
		$article->save();
	}

	private function markdown(\SplFileInfo $file)
	{
		$body = $this->markdown->text(file_get_contents($file->getPathname()));
		$body = preg_replace_callback('#<h1[^>]*>([^<]+)</h1>#', function($matches) use(&$title) {

			$title = $matches[1];

			return '';

		}, $body);

		if (!$title)
		{
			throw new \LogicException("Unable to locate article title.");
		}

		return [ $title, trim($body) ];
	}
}

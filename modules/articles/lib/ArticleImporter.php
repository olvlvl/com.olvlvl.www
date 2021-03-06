<?php

namespace App\Modules\Articles;

use ICanBoogie\DateTime;
use function ICanBoogie\excerpt;
use function preg_replace;
use function strpos;

final class ArticleImporter
{
	private const ALLOWED_TAGS = [ 'p', 'code', 'del', 'em', 'ins', 'strong' ];

	/**
	 * Returns the hash of an article file.
	 *
	 * @param \SplFileInfo $file
	 *
	 * @return string
	 */
	static private function hash(\SplFileInfo $file)
	{
		return base64_encode(hash_file('sha384', $file->getPathname(), 'true'));
	}

	/**
	 * @var ArticleModel
	 */
	private $model;

	/**
	 * @var \Parsedown
	 */
	private $markdown;

	public function __construct(ArticleModel $model)
	{
		$this->model = $model;
		$this->markdown = new \Parsedown();
	}

	/**
	 * @param \SplFileInfo $file
	 *
	 * @return Article
	 *
	 * @throws \ReflectionException
	 */
	public function __invoke(\SplFileInfo $file): Article
	{
		$filename = $file->getFilename();
		$date_string = substr($filename, 0, 8);
		$date = DateTime::from($date_string . '120000', 'Europe/Berlin');
		$slug = basename(substr($filename, 9), '.md');
		$hash = self::hash($file);

		/* @var $article Article */

		$article = $this->model->where('date = ? AND slug = ?', $date, $slug)->one;

		if ($article)
		{
			if ($article->hash === $hash)
			{
				return $article;
			}
		}
		else
		{
			$article = Article::from([

				'slug' => $slug,
				'date' => $date

			]);
		}

		[ $title, $body ] = $this->markdown($file);
		$excerpt = $this->excerpt($body);

		$article->assign(compact('title', 'body', 'excerpt', 'hash'))->save();

		return $article;
	}

	/**
	 * @param \SplFileInfo $file
	 *
	 * @return array An array with title and body.
	 */
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

		$body = preg_replace("/\~\~(.+)\~\~/", "<del>$1</del>", $body);
		$body = str_replace('<table>', '<table class="table table-bordered">', $body);

		return [ $title, trim($body) ];
	}

	/**
	 * Creates an excerpt of a body of text.
	 *
	 * @param string $body
	 *
	 * @return string
	 */
	private function excerpt($body)
	{
		$separator_position = strpos($body, '</p>');

		if ($separator_position !== false)
		{
			$body = trim(substr($body, 0, $separator_position));
		}

		$excerpt = excerpt($body);
		$excerpt = strip_tags($excerpt, '<' . implode('><', self::ALLOWED_TAGS) . '>');

		if (strpos($excerpt, '[…]') === false)
		{
			$excerpt = preg_replace('/\.?\<\/p\>$/m', ' <span class="excerpt-warp">[…]</span></p>', $excerpt);
		}

		return $excerpt;
	}
}

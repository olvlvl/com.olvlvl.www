<?php

namespace App\Modules\Articles;

use App\Application\Markdown;
use App\Application\StrUtil;
use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\Binding\ActiveRecord\Record;
use ICanBoogie\DateTime;
use LogicException;
use SplFileInfo;

use function file_get_contents;
use function preg_replace;
use function preg_replace_callback;
use function strpos;

final class ArticleImporter
{
	private const ALLOWED_TAGS = [ 'p', 'code', 'del', 'em', 'ins', 'strong' ];

	/**
	 * Returns the hash of an article file.
	 */
	static private function hash(SplFileInfo $file): string
	{
		return base64_encode(hash_file('sha384', $file->getPathname(), 'true'));
	}

	public function __construct(
		#[Record(Article::class)]
		private readonly Model $model,
		private readonly Markdown $markdown,
	) {
	}

	public function __invoke(SplFileInfo $file): Article
	{
		$filename = $file->getFilename();
		$date_string = substr($filename, 0, 8);
		$date = DateTime::from($date_string . '120000', 'Europe/Berlin');
		$slug = basename(substr($filename, 9), '.md');
		$hash = self::hash($file);

		$article = $this->model->where('date = ? AND slug = ?', $date, $slug)->one;

		if ($article) {
			if ($article->hash === $hash) {
				return $article;
			}
		} else {
			$article = Article::from([

				'slug' => $slug,
				'date' => $date

			]);
		}

		[ $title, $body ] = $this->markdown($file);
		$excerpt = $this->excerpt($body);

		$article
			->assign(compact('title', 'body', 'excerpt', 'hash'))
			->save();

		return $article;
	}

	/**
	 * @param SplFileInfo $file
	 *
	 * @return array An array with title and body.
	 */
	private function markdown(SplFileInfo $file): array
	{
		$markdown = file_get_contents($file->getPathname());
		$html = $this->markdown->text($markdown);
		$html = preg_replace_callback('/<h1[^>]*>(.+)<\/h1>/', function ($matches) use (&$title) {
			$title = $matches[1];

			return '';
		}, $html);

		if (!$title) {
			throw new LogicException("Unable to locate article title in {$file->getFilename()}");
		}

		return [ $title, trim($html) ];
	}

	/**
	 * Creates an excerpt of a text.
	 */
	private function excerpt(string $body): string
	{
		$separator_position = strpos($body, '</p>');

		if ($separator_position !== false) {
			$body = trim(substr($body, 0, $separator_position));
		}

		$excerpt = StrUtil::excerpt($body);
		$excerpt = strip_tags($excerpt, '<' . implode('><', self::ALLOWED_TAGS) . '>');

		if (!str_contains($excerpt, '[…]')) {
			$excerpt = preg_replace('/\.?\<\/p\>$/m', ' <span class="excerpt-warp">[…]</span></p>', $excerpt);
		}

		return $excerpt;
	}
}

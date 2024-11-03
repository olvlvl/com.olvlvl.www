<?php

namespace App\Presentation\HTTP;

use App\Modules\Articles\Article;
use ICanBoogie\Binding\Routing\Attribute\Get;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\HTTP\RedirectResponse;
use ICanBoogie\HTTP\ResponseStatus;
use ICanBoogie\Routing\Controller\ActionTrait;
use ICanBoogie\Routing\ControllerAbstract;
use ICanBoogie\View\RenderTrait;
use ICanBoogie\View\ViewProvider;

use function array_merge;
use function count;
use function html_entity_decode;
use function str_replace;
use function strip_tags;

final class ArticleController extends ControllerAbstract
{
	private const CONTINUE_READING_COUNT = 3;

	/**
	 * @uses list
	 * @uses show
	 * @uses show_redirect
	 * @uses feed
	 */
	use ActionTrait;
	use RenderTrait;

	public function __construct(
		private readonly ViewProvider $view_provider,
	) {
	}

	#[Get('/')]
	private function list(): void
	{
		$articles = Article::query()->order('-date')->all;

		$this->response->headers->cache_control = 'public';
		$this->response->expires = '+3 hour';

		$this->render($articles);
	}

	/**
	 * @throws NotFound
	 */
	#[Get("/<year:\d{4}>-<month:\d{2}>-:slug.html")]
	private function show(string $year, string $month, string $slug): void
	{
		$article = $this->find_one($year, $month, $slug);

		$this->response->headers->cache_control = 'public';
		$this->response->expires = '+3 hour';

		$this->render($article, locals: [
			'page_title' => $article->title,
			'og_url' => $article->url,
			'og_description' => $this->format_description($article->excerpt),
			'continue_reading' => $this->find_continue_reading($article),
		]);
	}

	/**
	 * @throws NotFound
	 */
	private function find_one(string $year, string $month, string $slug): Article
	{
		return Article::where('strftime("%Y", `date`) = ? AND strftime("%m", `date`) = ? AND slug = ?', $year, $month, $slug)
			->one ?: throw new NotFound;
	}

	/**
	 * @return iterable<Article>
	 */
	private function find_continue_reading(Article $record): iterable
	{
		$articles = Article::where('{primary} != ? AND date < ?', $record->article_id, $record->date)
			->order('-date')
			->take(self::CONTINUE_READING_COUNT)
			->all;

		$more = self::CONTINUE_READING_COUNT - count($articles);

		if ($more) {
			$articles = array_merge(
				$articles,
				Article::query()->order('-date')->take($more)->all
			);
		}

		return $articles;
	}

	/**
	 * @throws NotFound
	 */
	#[Get("/<year:\d{4}>-<month:\d{2}>-:slug")]
	private function show_redirect(string $year, string $month, string $slug): RedirectResponse
	{
		$record = $this->find_one($year, $month, $slug);

		return new RedirectResponse($record->url, ResponseStatus::STATUS_MOVED_PERMANENTLY);
	}

	#[Get("/index.atom")]
	private function feed(): void
	{
		$articles = Article::query()->take(20)->order('-date')->all;
		/* @var Article $first_article */
		$first_article = reset($articles);

		$this->response->headers->cache_control = 'public';
		$this->response->headers->content_type = 'application/atom+xml';
		$this->response->headers->content_type->charset = 'UTF-8';
		$this->response->expires = '+1 week';

		$this->render($articles, layout: 'feed', locals: [
			'updated' => $first_article->date
		]);
	}

	private function format_description(string $excerpt): string
	{
		return str_replace("\n", " ", html_entity_decode(strip_tags($excerpt)));
	}
}

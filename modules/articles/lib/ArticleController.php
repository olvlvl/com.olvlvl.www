<?php

namespace App\Modules\Articles;

use ICanBoogie\ActiveRecord\Query;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\HTTP\RedirectResponse;
use ICanBoogie\HTTP\ResponseStatus;
use ICanBoogie\Routing\Controller\ActionTrait;
use ICanBoogie\Routing\ControllerAbstract;
use ICanBoogie\View\View;
use ICanBoogie\View\ViewProvider;
use ICanBoogie\View\ViewTrait;

use function html_entity_decode;
use function str_replace;
use function strip_tags;

final class ArticleController extends ControllerAbstract
{
	/**
	 * @uses list
	 * @uses show
	 * @uses feed
	 */
	use ActionTrait;
	use ViewTrait;

	public const ACTION_FEED = 'feed';

	public function __construct(
		private readonly ArticleModel $model,
		private readonly ViewProvider $view_provider,
	) {
	}

	private function list(): View
	{
		$articles = $this->model->order('date DESC')->all;

		$this->response->headers->cache_control = 'public';
		$this->response->expires = '+3 hour';

		return $this->view($articles);
	}

	/**
	 * @throws NotFound
	 */
	private function show(string $year, string $month, string $slug): View
	{
		$article = $this->find_one($year, $month, $slug);

		$this->response->headers->cache_control = 'public';
		$this->response->expires = '+3 hour';

		return $this->view($article, locals: [
			'page_title' => $article->title,
			'og_url' => $article->url,
			'og_description' => $this->format_description($article->excerpt),
			'continue_reading' => $this->resolve_continue_reading($article),
		]);
	}

	/**
	 * @throws NotFound
	 */
	private function show_redirect(string $year, string $month, string $slug): RedirectResponse
	{
		$record = $this->find_one($year, $month, $slug);

		return new RedirectResponse($record->url, ResponseStatus::STATUS_MOVED_PERMANENTLY);
	}

	private function feed(): View
	{
		$articles = $this->model->limit(20)->order('date DESC')->all;
		/* @var Article $first_article */
		$first_article = reset($articles);

		$this->response->headers->cache_control = 'public';
		$this->response->headers->content_type = 'application/atom+xml';
		$this->response->headers->content_type->charset = 'UTF-8';
		$this->response->expires = '+1 week';

		return $this->view($articles, layout: 'feed', locals: [
			'updated' => $first_article->date
		]);
	}

	/**
	 * @throws NotFound
	 */
	private function find_one(string $year, string $month, string $slug): Article
	{
		/* @var Article $record */
		$record = $this->model
			->where('strftime("%Y", `date`) = ? AND strftime("%m", `date`) = ? AND slug = ?', $year, $month, $slug)
			->one;

		if (!$record) {
			throw new NotFound;
		}

		return $record;
	}

	private function format_description(string $excerpt): string
	{
		return str_replace("\n", " ", html_entity_decode(strip_tags($excerpt)));
	}

	private function resolve_continue_reading(Article $record): Query
	{
		return $this
			->model
			->where('{primary} != ?', $record->article_id)
			->order('RANDOM()')
			->limit(3);
	}
}

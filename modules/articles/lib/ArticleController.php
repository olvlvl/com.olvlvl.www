<?php

namespace App\Modules\Articles;

use App\Presentation\Controller\ControllerAbstract;
use ICanBoogie\ActiveRecord\Query;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\HTTP\Request;
use ICanBoogie\Render\Renderer;
use ICanBoogie\Routing\Controller\ActionTrait;

use ICanBoogie\View\View;

use function html_entity_decode;
use function ICanBoogie\app;
use function ICanBoogie\emit;
use function str_replace;
use function strip_tags;

/**
 * @property Module $module
 * @property ArticleModel $model
 */
class ArticleController extends ControllerAbstract
{
	/**
	 * @uses list
	 * @uses show
	 * @uses feed
	 */
	use ActionTrait;

	const ACTION_FEED = 'feed';

	/**
	 * @inheritdoc
	 */
	protected function get_name(): string
	{
		return 'articles';
	}

	private function list(): void
	{
		$this->response->headers->cache_control = 'public';
		$this->response->expires = '+3 hour';
		$this->view->content = $this->model->order('date DESC')->all;
	}

	/**
	 * @throws NotFound
	 */
	private function show(Request $request, string $year, string $month, string $slug): void
	{
		/* @var Article $record */
		$record = $this->model
			->where('strftime("%Y", `date`) = ? AND strftime("%m", `date`) = ? AND slug = ?', $year, $month, $slug)
			->one;

		if (!$record) {
			throw new NotFound;
		}

		$this->response->headers->cache_control = 'public';
		$this->response->expires = '+3 hour';
//		$this->view->content = $record;
//		$this->view['page_title'] = $record->title;
//		$this->view['continue_reading'] = $this->resolve_continue_reading($record);
//		$this->view['og_url'] = $record->url;
//		$this->view['og_description'] = $this->format_description($record->excerpt);

		$this->view($record)->assign([

			'page_title' => $record->title,
			'continue_reading' => $this->resolve_continue_reading($record),
			'og_url' => $record->url,
			'og_description' => $this->format_description($record->excerpt),

		]);
	}

	private function feed(): void
	{
		$articles = $this->model->limit(20)->order('date DESC')->all;
		/* @var Article $first_article */
		$first_article = reset($articles);

		$this->response->headers->cache_control = 'public';
		$this->response->expires = '+1 week';
		$this->response->headers->content_type = 'application/atom+xml';
		$this->response->headers->content_type->charset = 'UTF-8';
		$this->view->content = $articles;
		$this->view->layout = 'feed';
		$this->view['updated'] = $first_article->date;
	}

	private function view(
		mixed $content = null,
		string $template = null,
		string $layout = null,
	) {
		$view = new View($this, app()->container->get(Renderer::class));
		$view->content = $content;

		if ($template) {
			$view->template = $template;
		}

		if ($layout) {
			$view->layout = $layout;
		}

		emit(new View\AlterEvent($view));

		return $view;
	}

	private function format_description(string $excerpt): string
	{
		return str_replace("\n", " ", html_entity_decode(strip_tags($excerpt)));
	}

	private function resolve_continue_reading(Article $record): Query
	{
		return $this->model->where('{primary} != ?', $record->article_id)->order('RANDOM()')->limit(3);
	}
}

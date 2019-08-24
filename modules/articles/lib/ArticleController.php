<?php

namespace App\Modules\Articles;

use App\Presentation\Controller\ControllerAbstract;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\Routing\Controller\ActionTrait;
use function html_entity_decode;
use function str_replace;
use function strip_tags;

/**
 * @property Module $module
 * @property ArticleModel $model
 */
class ArticleController extends ControllerAbstract
{
	use ActionTrait;

	const ACTION_FEED = 'feed';

	/**
	 * @inheritdoc
	 */
	protected function get_name(): string
	{
		return 'articles';
	}

	protected function action_index()
	{
		$this->response->cache_control = 'public';
		$this->response->expires = '+3 hour';
		$this->view->content = $this->model->order('date DESC')->all;
	}

	/**
	 * @param int $year
	 * @param int $month
	 * @param string $slug
	 *
	 * @throws NotFound
	 */
	protected function action_show($year, $month, $slug)
	{
		/* @var Article $record */
		$record = $this->model
			->where('strftime("%Y", `date`) = ? AND strftime("%m", `date`) = ? AND slug = ?', $year, $month, $slug)
			->one;

		if (!$record)
		{
			throw new NotFound;
		}

		$this->response->cache_control = 'public';
		$this->response->expires = '+3 hour';
		$this->view->content = $record;
		$this->view['page_title'] = $record->title;
		$this->view['continue_reading'] = $this->resolve_continue_reading($record);
		$this->view['og_url'] = $record->url;
		$this->view['og_description'] = $this->format_description($record->excerpt);
	}

	protected function action_feed()
	{
		$articles = $this->model->limit(20)->order('date DESC')->all;
		/* @var Article $first_article */
		$first_article = reset($articles);

		$this->response->content_type = 'application/atom+xml';
		$this->response->content_type->charset = 'UTF-8';
		$this->view->content = $articles;
		$this->view->layout = 'feed';
		$this->view['updated'] = $first_article->date;
	}

	private function format_description(string $excerpt): string
	{
		return str_replace("\n", " ", html_entity_decode(strip_tags($excerpt)));
	}

	/**
	 * @param Article $record
	 *
	 * @return \ICanBoogie\ActiveRecord\Query
	 */
	private function resolve_continue_reading(Article $record)
	{
		return $this->model->where('{primary} != ?', $record->article_id)->order('RANDOM()')->limit(3);
	}
}

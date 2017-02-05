<?php

namespace App\Modules\Articles;

use App\Presentation\Controller\ControllerAbstract;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\Routing\Controller\ActionTrait;

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
	protected function get_name()
	{
		return 'articles';
	}

	protected function action_index()
	{
		$this->view->content = $this->model->order('date DESC')->all;
		$this->view['page_title'] = "Olivier Laviale, software architect";
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
	}

	protected function action_feed()
	{
		$articles = $this->model->limit(20)->order('date DESC')->all;
		/* @var Article $first_article */
		$first_article = reset($articles);

		$this->view->content = $articles;
		$this->view->layout = 'feed';
		$this->view['updated'] = $first_article->date;
		$this->response->content_type = 'application/atom+xml';
	}

	/**
	 * @param Article $record
	 *
	 * @return \ICanBoogie\ActiveRecord\Query
	 */
	private function resolve_continue_reading(Article $record)
	{
		return $this->model->where('{primary} != ?', $record->article_id)->order('RANDOM()')->limit(5);
	}
}

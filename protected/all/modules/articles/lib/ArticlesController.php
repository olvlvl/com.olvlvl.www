<?php

namespace App\Modules\Articles;

use App\Controller;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\Routing\Controller\ActionTrait;

/**
 * @property Module $module
 */
class ArticlesController extends Controller
{
	use ActionTrait;

	protected function action_index()
	{
		$this->view->content = $this->group_by_month($this->model->order('date DESC')->all);
		$this->view['page_title'] = "Olivier Laviale, software architect";
	}

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

	/**
	 * @param Article[] $records
	 *
	 * @return array
	 */
	private function group_by_month(array $records)
	{
		$groups = [];

		foreach ($records as $record)
		{
			$key = "{$record->year}-{$record->month}";

			$groups[$key][] = $record;
		}

		return $groups;
	}

	private function resolve_continue_reading(Article $record)
	{
		return $this->model->where('{primary} != ?', $record->article_id)->order('RANDOM()')->limit(5);
	}
}

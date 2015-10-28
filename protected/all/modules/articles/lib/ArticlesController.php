<?php

namespace App\Modules\Articles;

use App\Controller;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\Routing\Controller\ActionTrait;

class ArticlesController extends Controller
{
	use ActionTrait;

	protected function action_index()
	{
		$this->view->content = $this->fetch_records([ 'order' => '-date' ]);
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
		$this->response->expires = '+3 month';
		$this->view->content = $record;
		$this->view['page_title'] = $record->title;
	}
}

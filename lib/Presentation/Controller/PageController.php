<?php

namespace App\Presentation\Controller;

use const App\PAGES;
use ICanBoogie\Routing\Controller\ActionTrait;

class PageController extends ControllerAbstract
{
	use ActionTrait;

	protected function action_about()
	{
		$this->response->cache_control = 'public';
		$this->response->expires = '+3 hour';
		$this->view->template = 'page/show';
		$this->view->content = $this->app->template_engines->render(PAGES . '/about.md', null, []);
		$this->view['page_title'] = "About Olivier Laviale";
	}
}

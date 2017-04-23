<?php

namespace App\Presentation\Controller;

use ICanBoogie\Routing\Controller\ActionTrait;

class PageController extends ControllerAbstract
{
	use ActionTrait;

	protected function action_about()
	{
		$this->view->template = 'page/show';
		$this->view->content = $this->app->template_engines->render('_pages/about.md', null, []);
		$this->view['page_title'] = "About Olivier Laviale";
	}
}

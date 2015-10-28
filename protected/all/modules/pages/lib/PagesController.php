<?php

namespace App\Modules\Pages;

use App\Controller;
use ICanBoogie\Binding\Routing\ForwardUndefinedPropertiesToApplication;
use ICanBoogie\Routing\Controller\ActionTrait;
use ICanBoogie\Module\CoreBindings as ModuleBindings;

class PagesController extends Controller
{
	use ActionTrait, ForwardUndefinedPropertiesToApplication;
	use ModuleBindings;

	protected function action_home()
	{
		$this->response->cache_control = 'public';
		$this->response->expires = '+1 month';
		$this->view['articles'] = $this->models['articles']->order('date DESC')->limit(10)->all;
	}
}

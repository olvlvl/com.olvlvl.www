<?php

namespace App\Presentation\Listener;

use ICanBoogie\Binding\Event\Listener;
use ICanBoogie\View\View;

use function ICanBoogie\normalize;

final class AlterViewListener
{
	#[Listener]
	public function __invoke(View\AlterEvent $event, View $target): void
	{
		$route = $target->controller->route;

		$target['body_css'] = 'page-' . normalize($route->id ?? $route->action);
	}
}

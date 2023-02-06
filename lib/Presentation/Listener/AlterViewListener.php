<?php

namespace App\Presentation\Listener;

use ICanBoogie\View\View;

use function ICanBoogie\normalize;

final class AlterViewListener
{
	public function __invoke(View\AlterEvent $event, View $sender): void
	{
		$route = $event->controller->route;

		$sender['body_css'] = 'page-' . normalize($route->id ?? $route->action);
	}
}

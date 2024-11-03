<?php

namespace App\Presentation\Listener;

use ICanBoogie\Event\Listen;
use ICanBoogie\View\View;

use function ICanBoogie\normalize;
use function ICanBoogie\trim_prefix;

final class AlterViewListener
{
	#[Listen]
	public function __invoke(View\AlterEvent $event, View $sender): void
	{
		$route = $event->controller->route;

		$slug = trim_prefix(normalize($route->id ?? $route->action), 'pages-');

		$sender['body_css'] = 'page-' . $slug;
	}
}
<?php

namespace App\Presentation\Handler;

use ICanBoogie\View\View;

use function ICanBoogie\normalize;

final class ViewAlterHandler
{
	public function __invoke(View\AlterEvent $event, View $target): void
	{
		$route = $target->controller->route;

		$target['body_css'] = 'page-' . normalize($route->id ?? $route->action);
	}
}

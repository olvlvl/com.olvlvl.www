<?php

namespace App\Presentation\Handler;

use function ICanBoogie\normalize;
use ICanBoogie\View\View;

final class ViewAlterHandler
{
	public function __invoke(View\AlterEvent $event, View $target): void
	{
		$target['body_css'] = 'page-' . normalize($target->controller->route->id);
	}
}

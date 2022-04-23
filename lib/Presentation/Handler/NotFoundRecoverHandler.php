<?php

namespace App\Presentation\Handler;

use ICanBoogie\HTTP\NotFound;
use ICanBoogie\HTTP\RecoverEvent;
use ICanBoogie\HTTP\Response;
use ICanBoogie\Render\Renderer;
use ICanBoogie\Render\RenderOptions;

final class NotFoundRecoverHandler
{
	public function __construct(
		private readonly Renderer $renderer
	) {
	}

	public function __invoke(RecoverEvent $event, NotFound $target): void
	{
		$html = $this->renderer->render(
			$target,
			new RenderOptions(
				template: '404',
				layout: 'default',
				locals: [ 'body_css' => 'page-exception' ]
			)
		);

		$event->response = new Response($html, $target->getCode());
		$event->stop(); // disable default exception handler
	}
}

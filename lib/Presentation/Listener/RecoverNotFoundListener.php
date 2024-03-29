<?php

namespace App\Presentation\Listener;

use ICanBoogie\HTTP\NotFound;
use ICanBoogie\HTTP\RecoverEvent;
use ICanBoogie\HTTP\Response;
use ICanBoogie\Render\Renderer;
use ICanBoogie\Render\RenderOptions;

final class RecoverNotFoundListener
{
	public function __construct(
		private readonly Renderer $renderer
	) {
	}

	public function __invoke(RecoverEvent $event, NotFound $sender): void
	{
		$html = $this->renderer->render(
			$sender,
			new RenderOptions(
				template: '404',
				layout: 'default',
				locals: [ 'body_css' => 'page-exception' ]
			)
		);

		$event->response = new Response($html, $sender->getCode());
		$event->stop(); // disable default exception handler
	}
}

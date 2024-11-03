<?php

namespace App\Presentation\Listener;

use ICanBoogie\Event\Listen;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\HTTP\RecoverEvent;
use ICanBoogie\HTTP\Response;
use ICanBoogie\Render\Renderer;
use ICanBoogie\Render\RenderOptions;

final readonly class RecoverNotFoundListener
{
	public function __construct(
		private Renderer $renderer
	) {
	}

	#[Listen]
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

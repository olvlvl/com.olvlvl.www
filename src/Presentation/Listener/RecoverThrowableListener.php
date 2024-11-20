<?php

namespace App\Presentation\Listener;

use Exception;
use ICanBoogie\Event\Listener;
use ICanBoogie\HTTP\RecoverEvent;
use ICanBoogie\HTTP\Response;
use ICanBoogie\HTTP\ResponseStatus;
use ICanBoogie\Render\Renderer;
use ICanBoogie\Render\RenderOptions;
use Throwable;

final readonly class RecoverThrowableListener
{
	public function __construct(
		private Renderer $renderer
	) {
	}

	#[Listener]
	public function __invoke(RecoverEvent $event, Exception $sender): void
	{
		try {
			$code = $sender->getCode();

			if ($code < 100 || $code >= 600) {
				$code = ResponseStatus::STATUS_INTERNAL_SERVER_ERROR;
			}

			$event->response = new Response($this->render_exception($sender), $code);
		} catch (Throwable) {
			#
			# we can't provide better… too bad
			#
		}
	}

	private function render_exception(Throwable $exception): string
	{
		return $this->renderer->render(
			$exception,
			new RenderOptions(
				template: 'exception',
				layout: 'default',
				locals: [
					'body_css' => 'page-exception'
				]
			)
		);
	}
}

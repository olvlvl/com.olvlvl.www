<?php

namespace App\Presentation\Handler;

use ICanBoogie\HTTP\Response;
use ICanBoogie\HTTP\Status;
use ICanBoogie\Exception\RescueEvent;
use ICanBoogie\Render\Renderer;
use Throwable;

final class ExceptionRescueHandler
{
	/**
	 * @var Renderer
	 */
	private $renderer;

	public function __construct(Renderer $renderer)
	{
		$this->renderer = $renderer;
	}

	public function __invoke(RescueEvent $event, Throwable $target): void
	{
		try
		{
			$code = $target->getCode();

			if ($code < 100 || $code >= 600)
			{
				$code = Status::INTERNAL_SERVER_ERROR;
			}

			$event->response = new Response($this->render_exception($target), $code);
		}
		catch (Throwable $e)
		{
			#
			# we can't provide better… too bad
			#
		}
	}

	private function render_exception(Throwable $exception): string
	{
		return $this->renderer->render($exception, [

			'template' => 'exception',
			'layout' => 'default',
			'locals' => [
				'body_css' => 'page-exception'
			]

		]);
	}
}

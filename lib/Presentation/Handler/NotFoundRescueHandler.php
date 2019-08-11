<?php

namespace App\Presentation\Handler;

use ICanBoogie\Exception\RescueEvent;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\HTTP\Response;
use ICanBoogie\Render\Renderer;

final class NotFoundRescueHandler
{
	/**
	 * @var Renderer
	 */
	private $renderer;

	/**
	 * @param Renderer $renderer
	 */
	public function __construct(Renderer $renderer)
	{
		$this->renderer = $renderer;
	}

	public function __invoke(RescueEvent $event, NotFound $target): void
	{
		$html = $this->renderer->render($target, [

			Renderer::OPTION_TEMPLATE => '404',
			Renderer::OPTION_LAYOUT => 'default',
			Renderer::OPTION_LOCALS => [

				'body_css' => 'page-exception'

			]

		]);

		$event->response = new Response($html, $target->getCode());
		$event->stop(); // disable default exception handler
	}
}

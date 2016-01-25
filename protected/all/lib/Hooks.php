<?php

namespace App;

use ICanBoogie\HTTP\Response;
use ICanBoogie\HTTP\Status;
use ICanBoogie\Routing\RouteDispatcher;
use ICanBoogie\View\View;

class Hooks
{
	static public function on_routing_dispatcher_dispatch(RouteDispatcher\DispatchEvent $event, RouteDispatcher $target)
	{
		$response = $event->response;

		if (!$response || !$response->body || $response->content_type->type != 'text/html')
		{
			return;
		}

		$response->body = self::render_stats() . $response->body;
	}

	static public function on_exception_rescue(\ICanBoogie\Exception\RescueEvent $event, \Exception $target)
	{
		try
		{
			$code = $target->getCode();

			if ($code < 100 || $code >= 600)
			{
				$code = Status::INTERNAL_SERVER_ERROR;
			}

			$event->response = new Response(self::render_exception($target), $code);
		}
		catch (\Exception $e)
		{
			#
			# we can't provide better… too bad
			#
		}
	}

	static public function on_view_alter(View\AlterEvent $event, View $target)
	{
		$target['helpers'] = [

			'render' => function() {

				return call_user_func_array([ self::app(), 'render' ], func_get_args());

			}

		];

		$target['body_css'] = 'page-' . \ICanBoogie\normalize($target->controller->route->id);
	}

	/*
	 * Support
	 */

	/**
	 * @param \Exception $exception
	 *
	 * @return string
	 */
	static public function render_exception(\Exception $exception)
	{
		return self::app()->render($exception, [

			'template' => 'exception',
			'layout' => 'default',
			'locals' => [
				'body_css' => 'page-exception'
			]

		]);
	}

	static private function render_stats()
	{
		$boot_time = round(($_SERVER['ICANBOOGIE_READY_TIME_FLOAT'] - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 3);
		$total_time = round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 3);

		return "<!-- booted in: $boot_time ms, completed in $total_time ms -->";
	}

	/**
	 * @return Application
	 */
	static private function app()
	{
		return \ICanBoogie\app();
	}
}

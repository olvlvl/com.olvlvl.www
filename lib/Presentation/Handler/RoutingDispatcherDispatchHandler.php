<?php

namespace App\Presentation\Handler;

use ICanBoogie\Routing\RouteDispatcher;

final class RoutingDispatcherDispatchHandler
{
	public function __invoke(RouteDispatcher\DispatchEvent $event, RouteDispatcher $target): void
	{
		$response = $event->response;

		if (!$response || !$response->body || $response->content_type->type != 'text/html')
		{
			return;
		}

		$response->body = self::render_stats() . $response->body;
	}

	/**
	 * @return string
	 */
	static private function render_stats()
	{
		$boot_time = self::format_time($_SERVER['ICANBOOGIE_READY_TIME_FLOAT'] - $_SERVER['REQUEST_TIME_FLOAT']);
		$total_time = self::format_time(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']);

		return "<!-- booted in: $boot_time ms, completed in $total_time ms -->";
	}

	/**
	 * @param float $micro_time
	 *
	 * @return float
	 */
	static private function format_time($micro_time)
	{
		return round($micro_time * 1000, 3);
	}
}

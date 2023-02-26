<?php

namespace App\Presentation\Listener;

use ICanBoogie\ConfigProfiler;
use ICanBoogie\Console\CallableDisplayName;
use ICanBoogie\Debug;
use ICanBoogie\EventProfiler;
use ICanBoogie\HTTP\Responder\WithEvent\RespondEvent;

use function array_fill;
use function array_values;
use function microtime;
use function sprintf;
use function str_repeat;
use function strlen;
use function substr;

use const PHP_EOL;

final class RespondEventListener
{
	public function __invoke(RespondEvent $event): void
	{
		$response = $event->response;

		if (!$response?->body || $response->headers->content_type->type !== 'text/html') {
			return;
		}

		$response->body = self::render_stats() . $response->body;
	}

	static private function render_stats(): string
	{
		$boot_time = self::format_time($_SERVER['ICANBOOGIE_READY_TIME_FLOAT'] - $_SERVER['REQUEST_TIME_FLOAT']);
		$total_time = self::format_time(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']);
		$delta_time = self::format_time(microtime(true) - $_SERVER['ICANBOOGIE_READY_TIME_FLOAT']);

		$more = '';

		if (Debug::is_dev()) {
			$more = PHP_EOL . PHP_EOL
				. self::render_config() . PHP_EOL
				. self::render_used_events() . PHP_EOL
				. self::render_unused_events() . PHP_EOL;
		}

		return "<!-- booted in $boot_time ms, completed in $total_time ms ($delta_time ms) $more-->";
	}

	static private function render_config(): string
	{
		$zero = $_SERVER['REQUEST_TIME_FLOAT'];
		$rows = [];
		$total_time = 0;

		foreach (ConfigProfiler::$entries as [$started_at, $finished_at, $name, $builder_class]) {
			$rows[] = [
				sprintf("%08.03f", ($started_at - $zero) * 1000),
				sprintf("%08.03f", ($finished_at - $started_at) * 1000),
				$name,
				$builder_class,
			];

			$total_time += $finished_at - $started_at;
		}

		$total_time_ms = sprintf("%.03f ms", $total_time * 1000);

		return self::render_table(
			"Config builders: $total_time_ms",
			[ "At", "Duration", "Config", "Builder" ],
			$rows
		);
	}

	static private function render_used_events(): string
	{
		$zero = $_SERVER['REQUEST_TIME_FLOAT'];
		$rows = [];
		$total_time = 0;

		foreach (EventProfiler::$calls as [$ended_at, $type, $hook, $started_at]) {
			$rows[] = [
				sprintf("%08.03f", ($started_at - $zero) * 1000),
				sprintf("%08.03f", ($ended_at - $started_at) * 1000),
				$type,
				CallableDisplayName::from($hook),
			];

			$total_time += $ended_at - $started_at;
		}

		$total_time_ms = sprintf("%.03f ms", $total_time * 1000);

		return self::render_table(
			"Used Events: $total_time_ms",
			[ "At", "Duration", "Type", "Callable" ],
			$rows
		);
	}

	static private function render_unused_events(): string
	{
		$zero = $_SERVER['REQUEST_TIME_FLOAT'];
		$rows = [];

		foreach (EventProfiler::$unused as [$time, $type]) {
			$rows[] = [
				sprintf("%08.03f", ($time - $zero) * 1000),
				$type,
			];
		}

		return self::render_table("Unused Events", [ "At", "Type" ], $rows);
	}

	static private function format_time(float $micro_time): float
	{
		return round($micro_time * 1000, 3);
	}

	static private function render_table(string $title, array $header, array $rows, array $footer = []): string
	{
		$max_per_columns = array_fill(0, count($rows[0]), 0);

		foreach ($rows as $row) {
			foreach (array_values($row) as $i => $column) {
				$max_per_columns[$i] = max($max_per_columns[$i], strlen($column));
			}
		}

		$pattern = '';
		$row_length = 0;

		foreach ($max_per_columns as $max) {
			$row_length += $max + 2;
			$pattern .= "%-{$max}s  ";
		}

		$row_length -= 2;
		$pattern = substr($pattern, 0, -2);

		$rc = $title . PHP_EOL . str_repeat('=', $row_length) . PHP_EOL;

		$rc .= sprintf($pattern, ...$header) . PHP_EOL;
		$rc .= str_repeat('-', $row_length) . PHP_EOL;

		foreach ($rows as $row) {
			$rc .= sprintf($pattern, ...$row) . PHP_EOL;
		}

		if ($footer) {
			$rc .= str_repeat('-', $row_length) . PHP_EOL;
			$rc .= sprintf($pattern, ...$footer) . PHP_EOL;
		}

		return $rc;
	}
}

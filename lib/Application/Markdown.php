<?php

namespace App\Application;

use Parsedown;

use function ICanBoogie\escape;
use function preg_replace;
use function preg_replace_callback;
use function str_replace;

class Markdown
{
	public function __construct(
		private readonly Parsedown $inner
	) {
	}

	public function text(string $markdown): string
	{
		$markdown = $this->preprocess($markdown);
		$html = $this->inner->text($markdown);

		return trim($this->postprocess($html));
	}

	public function line(string $markdown): string
	{
		$this->preprocess($markdown);
		$html = $this->inner->line($markdown);

		return trim($this->postprocess($html));
	}

	private function preprocess(string $markdown): string
	{
		$markdown = $this->preprocess_figure($markdown);

		return $markdown;
	}

	private function postprocess(string $html): string
	{
		$html = $this->postprocess_del($html);
		$html = $this->postprocess_table($html);

		return $html;
	}

	/**
	 * Transforms `![alt|caption](src)` into a `figure` element.
	 */
	private function preprocess_figure(string $markdown): string
	{
		return preg_replace_callback(
			'/\!\[(.+)\|([^\]]+)\]\(([^\)]+)\)/', function (array $matches) {
			$src = escape($matches[3]);
			$alt = escape($matches[1]);
			$caption = $this->inner->line($matches[2]);
			$caption = str_replace("\\n", "<br/>", $caption);

			return <<<HTML
				<figure><img src="$src" alt="$alt" /><figcaption>$caption</figcaption></figure>

				HTML;
		}, $markdown);
	}

	private function postprocess_del(string $html): string
	{
		return preg_replace("/\~\~(.+)\~\~/", "<del>$1</del>", $html);
	}

	private function postprocess_table(string $html): string
	{
		return str_replace('<table>', '<table class="table table-bordered">', $html);
	}
}

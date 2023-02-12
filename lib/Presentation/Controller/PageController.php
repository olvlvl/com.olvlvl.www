<?php

namespace App\Presentation\Controller;

use App\Application\Markdown;
use ICanBoogie\Routing\Controller\ActionTrait;
use ICanBoogie\Routing\ControllerAbstract;
use ICanBoogie\View\RenderTrait;
use ICanBoogie\View\ViewProvider;

use function file_get_contents;

use const App\PAGES;

final class PageController extends ControllerAbstract
{
	/**
	 * @uses pages_me
	 */
	use ActionTrait;
	use RenderTrait;

	public function __construct(
		private readonly ViewProvider $view_provider,
		private readonly Markdown $markdown
	) {
	}

	private function pages_me(): void
	{
		$content = $this->render_markdown(PAGES . '/resume.md');

		$this->response->headers->cache_control = 'public';
		$this->response->expires = '+3 hour';

		$this->render($content, template: 'page/show', locals: [
			'page_title' => "Olivier Laviale <small>About me / Résumé</small>"
		]);
	}

	private function render_markdown(string $filename): string
	{
		return $this->markdown->text(file_get_contents($filename));
	}
}

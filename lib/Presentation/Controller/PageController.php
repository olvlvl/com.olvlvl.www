<?php

namespace App\Presentation\Controller;

use ICanBoogie\Render\MarkdownEngine;
use ICanBoogie\Routing\Controller\ActionTrait;
use ICanBoogie\Routing\ControllerAbstract;
use ICanBoogie\View\RenderTrait;
use ICanBoogie\View\View;
use ICanBoogie\View\ViewProvider;

use const App\PAGES;

final class PageController extends ControllerAbstract
{
	/**
	 * @uses page_about
	 */
	use ActionTrait;
	use RenderTrait;

	public function __construct(
		private readonly ViewProvider $view_provider,
		private readonly MarkdownEngine $markdown
	) {
	}

	private function page_about(): View
	{
		$content = $this->render_markdown(PAGES . '/about.md');

		$this->response->headers->cache_control = 'public';
		$this->response->expires = '+3 hour';

		return $this->view($content, template: 'page/show', locals: [
			'page_title' => "About Olivier Laviale"
		]);
	}

	private function render_markdown(string $filename): string
	{
		return $this->markdown->render($filename, null, []);
	}
}

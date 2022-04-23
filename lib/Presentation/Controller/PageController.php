<?php

namespace App\Presentation\Controller;

use ICanBoogie\Render\MarkdownEngine;
use ICanBoogie\Routing\Controller\ActionTrait;

use const App\PAGES;

final class PageController extends ControllerAbstract
{
	/**
	 * @uses page_about
	 */
	use ActionTrait;

	public function __construct(
		private readonly MarkdownEngine $renderer
	) {
	}

	private function page_about(): void
	{
		$this->response->headers->cache_control = 'public';
		$this->response->expires = '+3 hour';
		$this->view->template = 'page/show';
		$this->view->content = $this->render_page(PAGES . '/about.md');
		$this->view['page_title'] = "About Olivier Laviale";
	}

	private function render_page(string $filename): string
	{
		return $this->renderer->render($filename, null, []);
	}
}

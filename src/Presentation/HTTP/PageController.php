<?php

namespace App\Presentation\HTTP;

use App\Application\Markdown;
use ICanBoogie\Binding\Routing\Attribute\Get;
use ICanBoogie\Routing\Controller\ActionTrait;
use ICanBoogie\Routing\ControllerAbstract;
use ICanBoogie\View\RenderTrait;
use ICanBoogie\View\ViewProvider;

use function file_get_contents;

final class PageController extends ControllerAbstract
{
	/**
	 * @uses me
	 */
	use ActionTrait;
	use RenderTrait;

    private const PAGES = "content/pages";

	public function __construct(
		private readonly ViewProvider $view_provider,
		private readonly Markdown $markdown
	) {
	}

	#[Get("/resume.html")]
	private function me(): void
	{
		$content = $this->render_markdown(self::PAGES . '/resume.md');

		$this->response->headers->cache_control = 'public';
		$this->response->expires = '+3 hour';

		$this->render($content, template: 'page/show', locals: [
			'page_title' => "Olivier Laviale",
            'document_title' => "Olivier Laviale, Staff Engineer",
            'nav_link' => 'resume',
		]);
	}

	private function render_markdown(string $filename): string
	{
		return $this->markdown->text(file_get_contents($filename));
	}
}

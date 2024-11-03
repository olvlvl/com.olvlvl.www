<?php

namespace App\Modules\Cache\Listener;

use ICanBoogie\Event\Listen;
use ICanBoogie\HTTP\Responder\WithEvent\RespondEvent;

use function file_put_contents;

final readonly class RespondEventListener
{
	public function __construct(
		private string $destination,
		private bool $enabled,
	) {
	}

	#[Listen]
	public function __invoke(RespondEvent $event): void
	{
		if (!$this->should_write($event)) {
			return;
		}

		$path = ltrim($event->request->path, '/') ?: 'index.html';
		$body = $event->response->body;

		file_put_contents($this->destination . $path, $body);
	}

	private function should_write(RespondEvent $event): bool
	{
		if (!$this->enabled) {
			return false;
		}

		if (!$event->request->method->is_get()) {
			return false;
		}

		$response = $event->response;

		if (!$response->status->is_ok) {
			return false;
		}

		if ($response->headers->content_type->type !== 'text/html') {
			return false;
		}

		return true;
	}
}

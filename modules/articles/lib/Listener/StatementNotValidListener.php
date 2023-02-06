<?php

namespace App\Modules\Articles\Listener;

use App\Modules\Articles\ArticleModel;
use App\Modules\Articles\ArticleSynchronizer;
use ICanBoogie\ActiveRecord\StatementNotValid;
use ICanBoogie\HTTP\RecoverEvent;
use ICanBoogie\HTTP\RedirectResponse;
use Throwable;

use function str_contains;

/**
 * Recovers missing "articles" table.
 *
 * The database is destroyed before when the server is started.
 */
final class StatementNotValidListener
{
	public function __construct(
		private readonly ArticleModel $model,
		private readonly ArticleSynchronizer $synchronizer
	) {
	}

	/**
	 * @throws Throwable
	 */
	public function __invoke(RecoverEvent $event, StatementNotValid $sender): void
	{
		if (!str_contains($sender->getMessage(), "no such table: articles")) {
			return;
		}

		$this->model->install();
		$this->synchronizer->synchronize();

		$event->response = new RedirectResponse($event->request->uri);
	}
}

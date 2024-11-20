<?php

namespace App\Modules\Articles\Listener;

use App\Modules\Articles\Article;
use App\Modules\Articles\ArticleSynchronizer;
use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\ActiveRecord\StatementNotValid;
use ICanBoogie\Binding\ActiveRecord\Record;
use ICanBoogie\Event\Listener;
use ICanBoogie\HTTP\RecoverEvent;
use ICanBoogie\HTTP\RedirectResponse;
use Throwable;

use function str_contains;

/**
 * Recovers missing "articles" table.
 *
 * The database is destroyed before when the server is started.
 */
final readonly class RecoverFromMissingTable
{
	public function __construct(
		#[Record(Article::class)]
		private Model $model,
		private ArticleSynchronizer $synchronizer
	) {
	}

	/**
	 * @throws Throwable
	 */
	#[Listener]
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

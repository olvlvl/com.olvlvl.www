<?php

namespace App\Modules\Articles\Listener;

use App\Modules\Articles\ArticleSynchronizer;
use ICanBoogie\Event\Listen;
use ICanBoogie\HTTP\Responder\WithEvent\BeforeRespondEvent;
use ICanBoogie\Module\ModuleInstaller;

use ICanBoogie\Module\ModuleInstaller\ModuleInstallFailed;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

use function file_exists;
use function filemtime;

final readonly class BeforeRespondEventListener
{
	/**
	 * @param string[] $article_locations
	 */
	public function __construct(
		#[Autowire(param: 'article_locations')]
		private array $article_locations,
		#[Autowire(param: 'database_location')]
		private string $database_location,
		private ArticleSynchronizer $synchronizer,
		private ModuleInstaller $module_installer,
	) {
	}

	/**
	 * @throws ModuleInstallFailed
	 */
	#[Listen]
	public function __invoke(BeforeRespondEvent $event): void
	{
		if (!$this->should_update( $database_exists)) {
			return;
		}

		if (!$database_exists) {
			$this->module_installer->install_all();
		}

		$this->synchronizer->synchronize();
	}

	private function should_update(&$database_exists): bool
	{
		$database_exists = file_exists($this->database_location);

		if (!$database_exists) {
			return true;
		}

		$database_mtime = filemtime($this->database_location);

		foreach ($this->article_locations as $directory) {
			if ($database_mtime < filemtime($directory)) {
				return true;
			}
		}

		return false;
	}
}

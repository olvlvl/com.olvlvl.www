<?php

namespace App\Modules\Articles;

use ICanBoogie\Core;
use ICanBoogie\Routing\Controller;

class Hooks
{
	/*
	 * Events
	 */

	/**
	 * @param Controller\BeforeActionEvent $event
	 * @param ArticleController $controller
	 */
	static public function before_controller_action(Controller\BeforeActionEvent $event, ArticleController $controller)
	{
		$exists = file_exists(\App\DATABASE);

		if ($exists && filemtime(\App\DATABASE) >= filemtime(\App\ROOT . '_articles'))
		{
			return;
		}

		if (!$exists)
		{
			$controller->app->modules->install();
		}

		$module = $controller->module;
		$module->sync();
	}

	/*
	 * Prototype
	 */

	static public function url(Article $record, $type = 'show')
	{
		if ($type === 'index')
		{
			return self::app()->url_for('articles:index') . "#on-{$record->year}-{$record->month}";
		}

		return self::app()->url_for("articles:show", $record);
	}

	/*
	 * Support
	 */

	/**
	 * @return \App\Application
	 */
	static private function app()
	{
		return \ICanBoogie\app();
	}
}

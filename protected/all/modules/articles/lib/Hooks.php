<?php

namespace App\Modules\Articles;

use ICanBoogie\Core;

class Hooks
{
	/*
	 * Events
	 */

	/**
	 * @param Core\RunEvent $event
	 * @param Core|\App\Application $app
	 */
	static public function on_core_run(Core\RunEvent $event, Core $app)
	{
		$exists = file_exists(\App\DATABASE);

		if ($exists && filemtime(\App\DATABASE) >= filemtime(\App\ROOT . '_articles'))
		{
			return;
		}

		if (!$exists)
		{
			$app->modules->install();
		}

		/* @var $module Module */

		$module = $app->modules['articles'];
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

<?php

namespace App\Modules\Articles;

use function ICanBoogie\app;

class Hooks
{
	/*
	 * Prototype
	 */

	static public function url(Article $record, $type = 'show')
	{
		if ($type === 'index')
		{
			return app()->url_for('articles:index') . "#on-{$record->year}-{$record->month}";
		}

		return app()->url_for("articles:show", $record);
	}
}

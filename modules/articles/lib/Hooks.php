<?php

namespace App\Modules\Articles;

use ICanBoogie\Routing\RouteMaker;
use LogicException;

use function ICanBoogie\app;

class Hooks
{
	/*
	 * Prototype
	 */

	static public function url(Article $record): string
	{
		return self::url_for($record);
	}

	static public function url_for(Article $record, $type = RouteMaker::ACTION_SHOW): string
	{
		return match ($type) {
			RouteMaker::ACTION_LIST => app()->url_for('articles:list') . "#on-{$record->year}-{$record->month}",
			RouteMaker::ACTION_SHOW => app()->url_for("articles:show", $record),
			default => throw new LogicException("Don't know the URL for `$type`.")
		};
	}
}

<?php

namespace App\Modules\Articles;

class Hooks
{
	static public function url(Article $record, $type = 'show')
	{
//		var_dump(func_get_args()); exit;
//		var_dump("articles:$type"); exit;

		return \ICanBoogie\app()->url_for("articles:show", $record);
	}
}

<?php

namespace App\Modules\Articles;

use ICanBoogie\HTTP\Request;
use ICanBoogie\Routing\RouteMaker as Make;

return Make::resource('articles', ArticlesController::class, [

	Make::OPTION_ID_NAME => 'article_id',
	Make::OPTION_ACTIONS => [

		Make::ACTION_SHOW => [ '/{name}/<year:\d{4}>-<month:\d{2}>-:slug', Request::METHOD_GET ]

	]

]);

<?php

namespace App\Modules\Articles;

use ICanBoogie\HTTP\Request;
use ICanBoogie\Routing\RouteMaker as Make;

return Make::resource('articles', ArticleController::class, [

	Make::OPTION_ID_NAME => 'article_id',
	Make::OPTION_ACTIONS => [

		Make::ACTION_INDEX => [ '/', Request::METHOD_GET ],
		Make::ACTION_SHOW => [ '/<year:\d{4}>-<month:\d{2}>-:slug', Request::METHOD_GET ],
		ArticleController::ACTION_FEED => [ '/index.atom', Request::METHOD_GET ]

	]

]);

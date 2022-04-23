<?php

namespace App\Modules\Articles;

use ICanBoogie\Binding\Routing\ConfigBuilder;
use ICanBoogie\HTTP\RequestMethod;
use ICanBoogie\Routing\RouteMaker as Make;

/**
 * @uses ArticleController::action_index
 * @uses ArticleController::action_show
 * @uses ArticleController::action_feed
 */
return function (ConfigBuilder $config): void {
	$config->resource(
		'articles',
		new Make\Options(
			id_name: 'article_id',
			basics: [

				Make::ACTION_LIST => new Make\Basics('/', RequestMethod::METHOD_GET),
				Make::ACTION_SHOW => new Make\Basics('/<year:\d{4}>-<month:\d{2}>-:slug', RequestMethod::METHOD_GET),
				ArticleController::ACTION_FEED => new Make\Basics('/index.atom', RequestMethod::METHOD_GET),

			]
		)
	);
};

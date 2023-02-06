<?php

namespace App\Modules\Articles;

use ICanBoogie\Binding\Routing\ConfigBuilder;
use ICanBoogie\HTTP\RequestMethod;
use ICanBoogie\Routing\RouteMaker as Make;

/**
 * @uses ArticleController::list()
 * @uses ArticleController::show()
 * @uses ArticleController::show_redirect()
 * @uses ArticleController::feed()
 */
return fn(ConfigBuilder $config) => $config
	->resource(
		name: 'articles',
		options: new Make\Options(
			id_name: 'article_id',
			basics: [

				Make::ACTION_LIST => new Make\Basics('/', RequestMethod::METHOD_GET),
				Make::ACTION_SHOW => new Make\Basics('/<year:\d{4}>-<month:\d{2}>-:slug.html', RequestMethod::METHOD_GET),
				ArticleController::ACTION_FEED => new Make\Basics('/index.atom', RequestMethod::METHOD_GET),

			]
		)
	)
	->get(
		pattern: '/<year:\d{4}>-<month:\d{2}>-:slug',
		action: 'articles:show_redirect'
	);

<?php

namespace App\Modules\Articles;

use ICanBoogie\Binding\Module\ConfigBuilder;

use function dirname;

return fn(ConfigBuilder $config) => $config
	->add_module(
		id: 'articles',
		class: Module::class,
		models: [ 'articles' ],
		path: dirname(__DIR__)
	);

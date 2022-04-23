<?php

namespace App\Modules\Articles;

use ICanBoogie\Binding\Prototype\ConfigBuilder;

return fn(ConfigBuilder $config) => $config
	->bind(Article::class, 'get_url', [ Hooks::class, 'url' ])
	->bind(Article::class, 'url', [ Hooks::class, 'url_for' ]);

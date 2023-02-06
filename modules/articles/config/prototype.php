<?php

namespace App\Modules\Articles;

use ICanBoogie\Binding\Prototype\ConfigBuilder;
use ICanBoogie\Binding\Routing\Prototype\UrlMethod;

use function ICanBoogie\Service\ref;

return fn(ConfigBuilder $config) => $config
	->bind(Article::class, UrlMethod::METHOD, ref(UrlMethod::class));

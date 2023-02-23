<?php

namespace App\Modules\Cache;

use ICanBoogie\Binding\Module\ConfigBuilder;

return fn(ConfigBuilder $config) => $config
	->add_module(id: 'cache', class: Module::class);

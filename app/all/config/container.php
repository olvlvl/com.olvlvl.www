<?php

use ICanBoogie\Binding\SymfonyDependencyInjection\ConfigBuilder;

return fn(ConfigBuilder $config) => $config
	->enable_caching();

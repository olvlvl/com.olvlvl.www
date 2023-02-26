<?php

use ICanBoogie\Binding\SymfonyDependencyInjection\ConfigBuilder;

return fn(ConfigBuilder $config) => $config
	->disable_caching();

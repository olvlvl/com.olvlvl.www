<?php

use ICanBoogie\Binding\Event\ConfigBuilder;

return fn(ConfigBuilder $config) => $config
	->use_attributes();

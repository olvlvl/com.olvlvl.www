<?php

use ICanBoogie\Binding\Routing\ConfigBuilder;

return fn(ConfigBuilder $config) => $config
	->use_attributes();

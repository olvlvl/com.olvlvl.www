<?php

use ICanBoogie\AppConfigBuilder;

return fn(AppConfigBuilder $config) => $config
	->enable_config_caching();

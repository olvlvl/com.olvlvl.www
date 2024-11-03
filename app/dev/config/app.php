<?php

use ICanBoogie\AppConfigBuilder;

return fn(AppConfigBuilder $config) => $config
	->disable_config_caching();

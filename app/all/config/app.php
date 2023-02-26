<?php

namespace ICanBoogie;

return fn(AppConfigBuilder $config) => $config
	->enable_config_caching();

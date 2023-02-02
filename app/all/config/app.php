<?php

namespace ICanBoogie;

return fn(AppConfigBuilder $config) => $config
	->enable_catalog_caching()
	->enable_config_caching()
	->enable_module_caching()
;

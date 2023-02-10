<?php

namespace ICanBoogie;

return fn(AppConfigBuilder $config) => $config
	->disable_catalog_caching()
	->disable_config_caching()
;

<?php

use ICanBoogie\Binding\Prototype\ConfigBuilder;
use ICanBoogie\Binding\Routing\Prototype\UrlMethod;

return fn(ConfigBuilder $config) => UrlMethod::bind($config);

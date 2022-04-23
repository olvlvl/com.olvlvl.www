<?php

use ICanBoogie\Binding\ActiveRecord\ConfigBuilder;

return fn(ConfigBuilder $config) => $config
	->add_connection('primary', 'sqlite:repository/db.sqlite');

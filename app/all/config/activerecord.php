<?php

use App\Modules\Articles\Article;
use ICanBoogie\Binding\ActiveRecord\ConfigBuilder;

return fn(ConfigBuilder $config) => $config
	->use_attributes()
	->add_connection('primary', 'sqlite:var/db.sqlite')
	->add_record(Article::class);

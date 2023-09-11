<?php

use App\Modules\Articles\Article;
use ICanBoogie\Binding\ActiveRecord\ConfigBuilder;

return fn(ConfigBuilder $config) => $config
	->use_attributes()
	->add_model(
		activerecord_class: Article::class,
//		schema_builder: fn(SchemaBuilder $schema) => $schema
//			->add_serial('article_id', primary: true)
//			->add_varchar('title')
//			->add_varchar('slug', size: 80)
//			->add_text('body')
//			->add_text('excerpt')
//			->add_date('date')
//			->add_varchar('hash'),
	);

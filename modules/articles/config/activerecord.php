<?php

use App\Modules\Articles\Article;
use App\Modules\Articles\ArticleModel;
use ICanBoogie\ActiveRecord\Schema;
use ICanBoogie\ActiveRecord\SchemaColumn;
use ICanBoogie\Binding\ActiveRecord\ConfigBuilder;

return fn(ConfigBuilder $config) => $config
	->add_model(
		'articles',
		new Schema([
			'article_id' => SchemaColumn::serial(primary: true),
			'title' => SchemaColumn::varchar(),
			'slug' => SchemaColumn::varchar(80),
			'body' => SchemaColumn::text(),
			'excerpt' => SchemaColumn::text(),
			'date' => new SchemaColumn('DATE'),
			'hash' => SchemaColumn::varchar(),
		]),
		activerecord_class: Article::class,
		model_class: ArticleModel::class,
	);

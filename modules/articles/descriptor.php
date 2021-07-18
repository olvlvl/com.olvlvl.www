<?php

namespace App\Modules\Articles;

use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\ActiveRecord\Schema;
use ICanBoogie\ActiveRecord\SchemaColumn;
use ICanBoogie\Module\Descriptor;

return [

	Descriptor::MODELS => [

		'primary' => [
			Model::SCHEMA => new Schema([
				'article_id' => SchemaColumn::serial(primary: true),
				'title' => SchemaColumn::varchar(),
				'slug' => SchemaColumn::varchar(80),
				'body' => new SchemaColumn('TEXT'),
				'excerpt' => new SchemaColumn('TEXT'),
				'date' => new SchemaColumn('DATE'),
				'hash' => SchemaColumn::varchar(),
			])
		]

	],

	Descriptor::TITLE => "Articles",
	Descriptor::NS => __NAMESPACE__

];

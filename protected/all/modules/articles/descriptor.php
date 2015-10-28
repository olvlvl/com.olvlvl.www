<?php

namespace App\Modules\Articles;

use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\Module\Descriptor;

return [

	Descriptor::MODELS => [

		'primary' => [

			Model::SCHEMA => [

				'article_id' => 'serial',
				'title' => 'varchar',
				'slug' => [ 'varchar', 80 ],
				'body' => 'text',
				'excerpt' => 'text',
				'date' => 'date',
				'hash' => 'varchar'

			]

		]

	],

	Descriptor::TITLE => "Articles",
	Descriptor::NS => __NAMESPACE__

];

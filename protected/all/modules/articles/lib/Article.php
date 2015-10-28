<?php

namespace App\Modules\Articles;

use ICanBoogie\ActiveRecord;

/**
 * @method string url(string $type)
 *
 * @property-read string $url
 * @property-read string $year
 * @property-read string $month
 */
class Article extends ActiveRecord
{
	const MODEL_ID = 'articles';

	public $article_id;
	public $title;
	public $slug;
	public $body;
	public $hash;

	use ActiveRecord\DateProperty;

	protected function get_year()
	{
		return $this->get_date()->year;
	}

	protected function get_month()
	{
		return $this->get_date()->format('m');
	}
}

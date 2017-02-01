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
	public $excerpt;
	public $hash;

	use ActiveRecord\Property\DateProperty;

	/**
	 * Returns a four digits year.
	 *
	 * @return int
	 */
	protected function get_year()
	{
		return $this->get_date()->year;
	}

	/**
	 * Returns a two digits month.
	 *
	 * @return string
	 */
	protected function get_month()
	{
		return $this->get_date()->format('m');
	}

	/**
	 * @inheritdoc
	 */
	public function create_validation_rules()
	{
		return [

			'title' => 'required',
			'slug' => 'required',
			'body' => 'required',
			'excerpt' => 'required',
			'hash' => 'required',

		];
	}
}

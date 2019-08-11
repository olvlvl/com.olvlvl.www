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

	static public function assignable(): array
	{
		return [ 'title', 'slug', 'body', 'excerpt', 'hash' ];
	}

	/**
	 * Returns a four digits year.
	 */
	protected function get_year(): int
	{
		return $this->get_date()->year;
	}

	/**
	 * Returns a two digits month.
	 */
	protected function get_month(): string
	{
		return $this->get_date()->format('m');
	}

	/**
	 * @inheritdoc
	 */
	public function create_validation_rules(): array
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

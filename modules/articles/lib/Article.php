<?php

namespace App\Modules\Articles;

use ICanBoogie\ActiveRecord;
use ICanBoogie\Binding\Routing\Prototype\UrlTrait;

use function strip_tags;

/**
 * @property-read string $year
 * @property-read string $month
 * @property-read string $safe_title
 */
class Article extends ActiveRecord
{
	use UrlTrait;
	use ActiveRecord\Property\DateProperty;

	public const MODEL_ID = 'articles';

	public int $article_id;
	public string $title;
	public string $slug;
	public string $body;
	public string $excerpt;
	public string $hash;

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

	protected function get_safe_title(): string
	{
		return strip_tags($this->title);
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

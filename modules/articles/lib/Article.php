<?php

namespace App\Modules\Articles;

use ICanBoogie\ActiveRecord;
use ICanBoogie\ActiveRecord\Schema\Character;
use ICanBoogie\ActiveRecord\Schema\Date;
use ICanBoogie\ActiveRecord\Schema\Id;
use ICanBoogie\ActiveRecord\Schema\Serial;
use ICanBoogie\ActiveRecord\Schema\Text;
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

	#[Id, Serial]
	public int $article_id;
	#[Character]
	public string $title;
	#[Character(unique: true)]
	public string $slug;
	#[Text]
	public string $body;
	#[Text]
	public string $excerpt;
	#[Character]
	public string $hash;
	#[Date]
	private $date;

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

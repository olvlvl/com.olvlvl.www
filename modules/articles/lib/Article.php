<?php

namespace App\Modules\Articles;

use ICanBoogie\ActiveRecord;
use ICanBoogie\ActiveRecord\Schema\Character;
use ICanBoogie\ActiveRecord\Schema\Date;
use ICanBoogie\ActiveRecord\Schema\Id;
use ICanBoogie\ActiveRecord\Schema\Index;
use ICanBoogie\ActiveRecord\Schema\Integer;
use ICanBoogie\ActiveRecord\Schema\Serial;
use ICanBoogie\ActiveRecord\Schema\Text;
use ICanBoogie\Binding\Routing\Prototype\UrlTrait;

use function strip_tags;

/**
 * @property-read string $year
 * @property-read string $month
 * @property-read string $safe_title
 */
#[Index('visibility')]
class Article extends ActiveRecord
{
	use UrlTrait;
	use ActiveRecord\Property\DateProperty;

	/**
	 * The article is discarded and not visible.
	 */
	public const VISIBILITY_NONE = 0;

	/**
	 * The article is only visible for local development.
	 */
	public const VISIBILITY_PRIVATE = 1;

	/**
	 * The article is only visible with a direct link.
	 */
	public const VISIBILITY_PROTECTED = 2;

	/**
	 * The article is public.
	 */
	public const VISIBILITY_PUBLIC = 3;

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
	#[Character(size: 64, fixed: true)]
	public string $hash;
	#[Date]
	private $date;

	/**
	 * @var self::VISIBILITY_*
	 */
	#[Integer(size: Integer::SIZE_TINY)]
	public int $visibility = self::VISIBILITY_NONE;

	public static function assignable(): array
	{
		return [ 'title', 'slug', 'body', 'excerpt', 'hash', 'visibility' ];
	}

	/**
	 * Returns a four-digit year.
	 */
	protected function get_year(): int
	{
		return $this->get_date()->year;
	}

	/**
	 * Returns a two-digit month.
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
			'visibility' => 'min:0;max:3',

		];
	}
}

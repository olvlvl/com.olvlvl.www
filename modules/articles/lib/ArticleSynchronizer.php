<?php

namespace App\Modules\Articles;

/**
 * Synchronizes articles.
 */
interface ArticleSynchronizer
{
	public function synchronize(): void;
}

<?php

namespace App\Modules\Articles;

class Module extends \ICanBoogie\Module
{
	public function update()
	{
		$di = new \DirectoryIterator(\App\ROOT . '_articles');
		$importer = new ArticleImporter($this->model);

		foreach ($di as $file)
		{
			if (!$file->isFile())
			{
				continue;
			}

			$importer($file);
		}
	}
}

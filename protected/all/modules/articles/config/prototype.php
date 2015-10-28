<?php

namespace App\Modules\Articles;

$hooks = Hooks::class . '::';

return [

	Article::class . '::get_url' => $hooks . 'url'

];

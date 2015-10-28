<?php

namespace App\Modules\Articles;

use ICanBoogie;

$hooks = Hooks::class . '::';

return [

	ICanBoogie\Core::class . '::run' => $hooks . 'on_core_run'

];

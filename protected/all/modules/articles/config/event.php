<?php

namespace App\Modules\Articles;

$hooks = Hooks::class . '::';

return [

	ArticlesController::class . '::action:before' => $hooks . 'before_controller_action'

];

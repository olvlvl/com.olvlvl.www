<?php

namespace App\Modules\Articles;

$hooks = Hooks::class . '::';

return [

	ArticleController::class . '::action:before' => $hooks . 'before_controller_action'

];

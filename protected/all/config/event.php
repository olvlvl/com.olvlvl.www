<?php

namespace App;

use ICanBoogie;

$hooks = Hooks::class . '::';

return [

	\Exception::class . '::rescue' => $hooks . 'on_exception_rescue',
	ICanBoogie\Routing\RouteDispatcher::class . '::dispatch' => $hooks . 'on_routing_dispatcher_dispatch',
	ICanBoogie\View\View::class . '::alter' => $hooks . 'on_view_alter'

];

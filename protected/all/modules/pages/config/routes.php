<?php

namespace App\Modules\Pages;

use ICanBoogie\HTTP\Request;
use ICanBoogie\Routing\RouteDefinition;

return [

	'home' => [

		RouteDefinition::PATTERN => '/',
		RouteDefinition::CONTROLLER => PagesController::class,
		RouteDefinition::ACTION => 'home',
		RouteDefinition::VIA => Request::METHOD_GET

	]

];

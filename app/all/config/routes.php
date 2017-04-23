<?php

namespace App;

use App\Presentation\Controller\PageController;
use ICanBoogie\Routing\RouteDefinition;

return [

	'about' => [

		RouteDefinition::PATTERN => '/about.html',
		RouteDefinition::CONTROLLER => PageController::class,
		RouteDefinition::ACTION => 'about'

	]

];

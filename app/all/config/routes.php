<?php

namespace App;

use function ICanBoogie\Service\ref;
use ICanBoogie\Routing\RouteDefinition;

/**
 * @uses \App\Presentation\Controller\PageController::action_about
 */

return [

	'about' => [

		RouteDefinition::PATTERN => '/about.html',
		RouteDefinition::CONTROLLER => ref('controller.page'),
		RouteDefinition::ACTION => 'about'

	]

];

<?php

namespace App;

use ICanBoogie\Binding\Routing\ControllerBindings as RoutingBindings;
use ICanBoogie\Module\ControllerBindings as ModuleBindings;
use ICanBoogie\View\ControllerBindings as ViewBindings;

/**
 * Base class for application controllers.
 */
abstract class Controller extends \ICanBoogie\Routing\Controller
{
	use ViewBindings, RoutingBindings, ModuleBindings;
}

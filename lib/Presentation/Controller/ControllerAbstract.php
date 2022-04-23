<?php

namespace App\Presentation\Controller;

use ICanBoogie\Binding\Module\ControllerBindings as ModuleBindings;
use ICanBoogie\Binding\Routing\ControllerBindings as RoutingBindings;
use ICanBoogie\View\ControllerBindings as ViewBindings;

/**
 * Base class for application controllers.
 */
abstract class ControllerAbstract extends \ICanBoogie\Routing\ControllerAbstract
{
	use ViewBindings, RoutingBindings, ModuleBindings;
}

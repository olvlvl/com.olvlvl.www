<?php

namespace App\Presentation\Controller;

use ICanBoogie\Binding\Routing\ControllerBindings as RoutingBindings;
use ICanBoogie\Module\ControllerBindings as ModuleBindings;
use ICanBoogie\Routing\Controller;
use ICanBoogie\View\ControllerBindings as ViewBindings;

/**
 * Base class for application controllers.
 */
abstract class ControllerAbstract extends Controller
{
	use ViewBindings, RoutingBindings, ModuleBindings;
}

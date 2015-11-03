<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use ICanBoogie\Core;
use ICanBoogie\Binding\Routing\CoreBindings as RoutingBindings;
use ICanBoogie\Binding\ActiveRecord\CoreBindings as ActiveRecordBindings;
use ICanBoogie\Module\CoreBindings as ModuleBindings;
use ICanBoogie\Binding\Render\CoreBindings as RenderBindings;

/**
 * Application class.
 */
class Application extends Core
{
	use ActiveRecordBindings, ModuleBindings, RoutingBindings, RenderBindings;
}

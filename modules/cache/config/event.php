<?php

namespace App\Modules\Cache\Listener;

use ICanBoogie\Binding\Event\ConfigBuilder;
use ICanBoogie\HTTP\Responder\WithEvent\RespondEvent;

use function ICanBoogie\Service\ref;

return fn(ConfigBuilder $config) => $config
	->attach(RespondEvent::class, ref(RespondEventListener::class));

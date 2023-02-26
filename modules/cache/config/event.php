<?php

namespace App\Modules\Cache\Listener;

use ICanBoogie\Application\ClearCacheEvent;
use ICanBoogie\Binding\Event\ConfigBuilder;
use ICanBoogie\HTTP\Responder\WithEvent\RespondEvent;

use function ICanBoogie\Service\ref;

return fn(ConfigBuilder $config) => $config
	->attach(ClearCacheEvent::class, [ ClearCacheListener::class, 'on' ])
	->attach(RespondEvent::class, ref(RespondEventListener::class));

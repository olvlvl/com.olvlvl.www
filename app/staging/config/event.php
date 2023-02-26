<?php

namespace App;

use App\Presentation\Listener\RespondEventListener;
use ICanBoogie\Binding\Event\ConfigBuilder;
use ICanBoogie\HTTP\Responder\WithEvent\RespondEvent;

use function ICanBoogie\Service\ref;

return fn(ConfigBuilder $config) => $config
	->attach(RespondEvent::class, ref(RespondEventListener::class));

<?php

namespace App;

use App\Presentation\Listener\AlterViewListener;
use App\Presentation\Listener\RecoverNotFoundListener;
use App\Presentation\Listener\RecoverThrowableListener;
use Exception;
use ICanBoogie\Binding\Event\ConfigBuilder;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\HTTP\RecoverEvent;
use ICanBoogie\View\View;
use ICanBoogie\View\View\AlterEvent;

use function ICanBoogie\Service\ref;

return fn(ConfigBuilder $config) => $config
	->attach_to(Exception::class, RecoverEvent::class, ref(RecoverThrowableListener::class))
	->attach_to(NotFound::class, RecoverEvent::class, ref(RecoverNotFoundListener::class))
	->attach_to(View::class, AlterEvent::class, ref(AlterViewListener::class));

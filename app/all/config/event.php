<?php

namespace App;

use App\Presentation\Listener\RecoverThrowableListener;
use App\Presentation\Listener\RecoverNotFoundListener;
use App\Presentation\Listener\RespondEventListener;
use App\Presentation\Listener\AlterViewListener;
use Exception;
use ICanBoogie\Binding\Event\ConfigBuilder;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\HTTP\RecoverEvent;
use ICanBoogie\HTTP\Responder\WithEvent\RespondEvent;
use ICanBoogie\View\View;
use ICanBoogie\View\View\AlterEvent;

use function ICanBoogie\Service\ref;

return fn(ConfigBuilder $config) => $config
	->attach(RespondEvent::class, ref(RespondEventListener::class))
	->attach_to(Exception::class, RecoverEvent::class, ref(RecoverThrowableListener::class))
	->attach_to(NotFound::class, RecoverEvent::class, ref(RecoverNotFoundListener::class))
	->attach_to(View::class, AlterEvent::class, ref(AlterViewListener::class));

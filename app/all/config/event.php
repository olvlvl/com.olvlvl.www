<?php

use ICanBoogie\Binding\Event\ConfigBuilder;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\HTTP\RecoverEvent;
use ICanBoogie\View\View;
use ICanBoogie\View\View\AlterEvent;

use function ICanBoogie\Service\ref;

/**
 * @uses \App\Presentation\Handler\ExceptionRescueHandler
 * @uses \App\Presentation\Handler\NotFoundRecoverHandler
 * @uses \App\Presentation\Handler\ViewAlterHandler
 */
return fn(ConfigBuilder $config) => $config
	->attach_to(Exception::class, RecoverEvent::class, ref('event.handler.exception.rescue'))
	->attach_to(NotFound::class, RecoverEvent::class, ref('event.handler.not_found.rescue'))
	->attach_to(View::class, AlterEvent::class, ref('event.handler.view.alter'));

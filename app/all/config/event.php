<?php

namespace ICanBoogie\Service;

/**
 * @uses \App\Presentation\Handler\ExceptionRescueHandler
 * @uses \App\Presentation\Handler\NotFoundRescueHandler
 * @uses \App\Presentation\Handler\RoutingDispatcherDispatchHandler
 * @uses \App\Presentation\Handler\ViewAlterHandler
 */

return [

	'Exception::rescue'                            => ref('event.handler.exception.rescue'),
	'ICanBoogie\HTTP\NotFound::rescue'             => ref('event.handler.not_found.rescue'),
	'ICanBoogie\Routing\RouteDispatcher::dispatch' => ref('event.handler.route_dispatcher.dispatch'),
	'ICanBoogie\View\View::alter'                  => ref('event.handler.view.alter'),

];

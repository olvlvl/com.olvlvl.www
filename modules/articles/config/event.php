<?php

namespace App\Modules\Articles;

use ICanBoogie\Binding\Event\ConfigBuilder;
use ICanBoogie\Routing\Controller\BeforeActionEvent;

use function ICanBoogie\Service\ref;

return fn(ConfigBuilder $config) => $config
	->attach_to(
		ArticleController::class,
		BeforeActionEvent::class,
		ref('event.handler.article_controller.before_action')
	);

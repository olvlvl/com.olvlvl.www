<?php

namespace App\Modules\Articles;

use App\Modules\Articles\Listener\BeforeControllerActionListener;
use App\Modules\Articles\Listener\StatementNotValidListener;
use ICanBoogie\ActiveRecord\StatementNotValid;
use ICanBoogie\Binding\Event\ConfigBuilder;
use ICanBoogie\HTTP\RecoverEvent;
use ICanBoogie\Routing\Controller\BeforeActionEvent;

use function ICanBoogie\Service\ref;

return fn(ConfigBuilder $config) => $config
	->attach_to(ArticleController::class, BeforeActionEvent::class, ref(BeforeControllerActionListener::class))
	->attach_to(StatementNotValid::class, RecoverEvent::class, ref(StatementNotValidListener::class));

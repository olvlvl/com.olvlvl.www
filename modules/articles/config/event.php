<?php

namespace App\Modules\Articles;

use App\Modules\Articles\Listener\BeforeControllerActionHandler;
use App\Modules\Articles\Listener\OnStatementNotValidRecord;
use ICanBoogie\ActiveRecord\StatementNotValid;
use ICanBoogie\Binding\Event\ConfigBuilder;
use ICanBoogie\HTTP\RecoverEvent;
use ICanBoogie\Routing\Controller\BeforeActionEvent;

use function ICanBoogie\Service\ref;

return fn(ConfigBuilder $config) => $config
	->attach_to(ArticleController::class, BeforeActionEvent::class, ref(BeforeControllerActionHandler::class))
	->attach_to(StatementNotValid::class, RecoverEvent::class, ref(OnStatementNotValidRecord::class))
;

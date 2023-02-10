<?php

namespace App\Modules\Articles;

use App\Modules\Articles\Listener\BeforeRespondEventListener;
use App\Modules\Articles\Listener\StatementNotValidListener;
use ICanBoogie\ActiveRecord\StatementNotValid;
use ICanBoogie\Binding\Event\ConfigBuilder;
use ICanBoogie\HTTP\RecoverEvent;
use ICanBoogie\HTTP\Responder\WithEvent\BeforeRespondEvent;

use function ICanBoogie\Service\ref;

return fn(ConfigBuilder $config) => $config
	->attach(BeforeRespondEvent::class, ref(BeforeRespondEventListener::class))
	->attach_to(StatementNotValid::class, RecoverEvent::class, ref(StatementNotValidListener::class));

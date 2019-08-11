<?php

namespace App\Modules\Articles;

use function ICanBoogie\Service\ref;

/**
 * @uses BeforeControllerActionHandler
 */

return [

	ArticleController::class . '::action:before' => ref('event.handler.article_controller.before_action')

];

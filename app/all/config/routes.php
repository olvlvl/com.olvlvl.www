<?php

use ICanBoogie\Binding\Routing\ConfigBuilder;

return fn(ConfigBuilder $config) => $config
	/**
	 * @uses \App\Presentation\Controller\PageController::page_about()
	 */
	->route('/about.html', 'page:about');

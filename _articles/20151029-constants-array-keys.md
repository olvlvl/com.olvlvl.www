# Use constants as array keys

The problem in using strings as array keys is there is no difference between the key used to define
the identifier of a route and that of a record. It can become quite troublesome when you need to
search for common keys such as `id`, `name`, or `title`, for instance when you need to rename `'id'`
to `'article_id'`, only for article records of course.

```php
<?php

$route = [ 'id' => 'articles:edit', … ];
$record = Record::from([ 'id' => 123 ]);
```

Another issue in using strings as array keys is that nothing prevents you from using misspelled,
deprecated, or extraneous keys.

For all these reasons I recommend to use constants such as `ROUTE_ID` and `ARTICLE_ID`. Your lovely
IDE will help you avoid misspells, and will even warn you when a constant is marked as deprecated.
But instead of defining constants in some namespace, I prefer to define them in classes or
interfaces with a clear purpose. For instance, I'd use constants such as `RouteDefinition::ID` and
`Article::ID`.

Consider the following route definition, using strings to define keys and some special values.

```php
<?php

$definition = [

	'pattern' => '/articles/<id:\d+>/edit',
	'controller' => ArticleController::class,
	'action' => 'edit',
	'via' => 'GET',
	'id' => 'articles:edit'

];
```

And now consider the following route definition, using constants:

```php
<?php

use ICanBoogie\HTTP\Request;
use ICanBoogie\RouteDefinition;

$definition = [

	RouteDefinition::PATTERN => '/articles/<id:\d+>/edit',
	RouteDefinition::CONTROLLER => ArticleController::class,
	RouteDefinition::ACTION => RouteDefinition::ACTION_EDIT,
	RouteDefinition::VIA => Request::METHOD_GET,
	RouteDefinition::ID => 'articles:edit'

];
```

That's a little more work, but I'm confident that these keys exists and now it would be child's play
to search for route definition identifiers.

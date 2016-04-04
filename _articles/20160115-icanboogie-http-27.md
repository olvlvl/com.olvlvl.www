# ICanBoogie/HTTP v2.7

So far, the revision v2.7 of [ICanBoogie/HTTP](https://github.com/ICanBoogie/HTTP) comes with the
following changes: safe and idempotent requests are supported with the `is_safe` and `is_idempotent`
properties; the request context now implements `ArrayAccess`; the `transform_params()` method
returns flatten and/or normalized request parameters.


<!-- body -->


## Safe and idempotent requests

Safe methods are HTTP methods that do not modify resources. For instance, using `GET` or `HEAD` on a
resource URL, should NEVER change the resource.

The `is_safe` property may be used to check if a request is safe or not.

```php
<?php

use ICanBoogie\HTTP\Request;

Request::from([ Request::OPTION_METHOD => Request::METHOD_GET ])->is_safe; // true
Request::from([ Request::OPTION_METHOD => Request::METHOD_POST ])->is_safe; // false
Request::from([ Request::OPTION_METHOD => Request::METHOD_DELETE ])->is_safe; // false
```

An idempotent HTTP method is a HTTP method that can be called many times without different outcomes.
It would not matter if the method is called only once, or ten times over. The result should be the
same.

The `is_idempotent` property may be used to check if a request is idempotent or not.

```php
<?php

use ICanBoogie\HTTP\Request;

Request::from([ Request::OPTION_METHOD => Request::METHOD_GET ])->is_idempotent; // true
Request::from([ Request::OPTION_METHOD => Request::METHOD_POST ])->is_idempotent; // false
Request::from([ Request::OPTION_METHOD => Request::METHOD_DELETE ])->is_idempotent; // true
```






## Request context

Because requests may be nested the request context offers a safe place where you can store the state
of your application that is relative to a request, for instance a request relative site, page,
route, dispatcher… The context may be used as an array, but is also a prototyped instance.

The following example demonstrates how to store a value in a request context:

```php
<?php

use ICanBoogie\HTTP\Request;

$request = Request::from($_SERVER);
$request->context['site'] = $app->models['sites']->one;
```

The following example demonstrates how to use the prototype feature to provide a value when it is
requested from the context:

```php
<?php

use ICanBoogie\HTTP\Request\Context;
use ICanBoogie\Prototype;

Prototype::from(Context::class)['lazy_get_site'] = function(Context $context) use ($site_model) {

	return $site_model->resolve_from_request($context->request);

};

$request = Request::from($_SERVER);

$site = $request->context['site'];
# or
$site = $request->context->site;
```




## Transforming request parameters

The `transform_params()` method returns a flattened and/or normalized parameters array:

```php
<?php

$request = Request::from([ Request::OPTION_REQUEST_PARAMS => [

	'a' => [ 'aa' => [ 1 ] ],
	'b' => '',
	'c' => '   ',
	'd' => "123"

] ]);

var_dump($request->transform_params(Request::TRANSFORM_NORMALIZE | Request::TRANSFORM_FLATTEN));
```

```
array(4) {
  'a[aa][0]' =>
  int(1)
  'b' =>
  NULL
  'c' =>
  NULL
  'd' =>
  string(3) "123"
}
```

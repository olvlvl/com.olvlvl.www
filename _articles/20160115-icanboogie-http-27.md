# ICanBoogie/HTTP v2.7

Work on ICanBoogie/HTTP v2.7 has started. The revision includes support for safe and idempotent
requests with the `is_safe` and `is_idempotent` properties, a request context that is implementing
`ArrayAccess`, and an method to return flatten and/or normalized request parameters.


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

# Hinting prototype methods and properties

The [Prototype package][] allows methods of classes using the [PrototypeTrait][] to be defined at
runtime; and since properties accessor are methods as well, this includes getters and setters.

Similar to interfaces, this feature allows classes to leave open implementation specifics. And since
attributes (their getter) can be used to lazy load a resource, it also allows for dependency
injection.

Since prototype methods may be added during runtime to any class implementing the
[PrototypeTrait][], prototyped classes may not be aware of the features that may be added to them.
For instance, the [Core][] instance is really a service provider as components add properties and
methods to expand the features of the application.

With all this magic going on it might be difficult to trace code or know the capabilities of an
object, and your IDE might not be happy about these _undefined_ methods and properties. To address
this issue components provide __prototype bindings__ that help hint your code.





## Prototype bindings

Prototype bindings are defined using traits and annotations. The following example demonstrates how
to define Active Record prototype bindings:

```php
<?php

namespace ICanBoogie\Binding\ActiveRecord;

use ICanBoogie\ActiveRecord\Connection;
use ICanBoogie\ActiveRecord\ConnectionCollection;
use ICanBoogie\ActiveRecord\ModelCollection;

/**
 * {@link \ICanBoogie\Core} prototype bindings.
 *
 * @property ConnectionCollection $connections
 * @property ModelCollection $models
 * @property Connection $db
 */
trait CoreBindings
{

}
```

Using this trait we can now hint that an instance has a `connections` property holding a
[ConnectionCollection][] instance, your IDE will happily provide suggestion and validate your code.

The following example demonstrates how to use this trait in a class:

```php
<?php

namespace App;

use ICanBoogie\Core;
use ICanBoogie\Binding;

class Application extends Core
{
	use Binding\ActiveRecord\CoreBindings;
}

$app = new Application;
$app->connections;
```

When using a trait in a class is not an option you can always use an annotation. For instance, the
[Core][] class doesn't use our trait, but we can still hint our code as follows:

```php
<?php

namespace App;

use ICanBoogie\Core;
use ICanBoogie\Binding;

/* @var $app Core|Binding\ActiveRecord\CoreBinding */

$app = new Core;
$app->connections;
```





## tl;dr

Traits describing prototype method bindings may be used to hint magical code. Your IDE is happy and
so are you.





[Core]:                 http://api.icanboogie.org/icanboogie/latest/class-ICanBoogie.Core.html
[ConnectionCollection]: http://api.icanboogie.org/activerecord/latest/class-ICanBoogie.ActiveRecord.ConnectionCollection.html
[ICanBoogie]:        https://github.com/ICanBoogie/ICanBoogie
[Prototype package]: https://github.com/ICanBoogie/Prototype
[PrototypeTrait]:    http://api.icanboogie.org/prototype/latest/class-ICanBoogie.PrototypeTrait.html

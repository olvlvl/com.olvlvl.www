# Binding Symfony's DIC to ICanBoogie

Although I really enjoy [the concept of bindings implemented in ICanBoogie][], I recognize it can be
challenging when your application has thousands of dependencies. Also, as I'm implementing a 
[message bus][] for [ICanBoogie][], I recognize the value of a dependency injection container to
instantiate all the lovely message handlers, and because I really like
[Symfony's Dependency Injection component][], I decided to use it for this first implementation,
trying to create something as transparent and flexible as possible.





## References

[ICanBoogie][] favors functional programming to interfaces in many places, and uses callables for a
lot of things such as controllers, event hooks, prototype methods, operations, proxy… Thus, I had
the idea to create service references as callables, as they would be used without requiring any code
change. They are very simple things really, they obtain the service they reference through a service
provider, pass the arguments, and return the result.

The following example demonstrates how to reference a service, and invoke it with some parameters.

```php
<?php

use function ICanBoogie\Service\ref;

$reference = ref('hello');
echo $reference("Olivier");
// Hello Olivier!
```

Of course, non-callable services can also be referenced, in which case the method `resolve()` is
used to obtain the service:

```php
<?php

use function ICanBoogie\Service\ref;

$reference = ref('my_non_callable_service');
$service = $reference->resolve();
$service->do_something();

# The reference forwards calls to the service, but that's a secret
$reference->do_something();
```





## The service provider

The service provider is created during the application boot event, and acts as a proxy for the
dependency injection container, which is only created when a reference needs to be resolved, or when
the container is explicitly required through `$app->container` or
`ServiceProvider::defined()->container`.





### Obtaining services bound to the application

Usually, ICanBoogie's components add getters to the `ICanBoogie\Application` instance through the
[prototype system][], which means the initial request is accessed with`$app->initial_request`, and
the session with `$app->session`. In order to have these available to the container, an extension
automatically add definitions for them. Now the session can also be obtained with
`ref('session')->resolve()`.





## Defining services

Services are defined using `services.yml` files in `config` folders. They are collected when it's time
to create the container, just like regular [configuration files][].

The following example is a sample of the services defined for my blog. You'll notice the `renderer`
service, which is actually bound to the `ICanBoogie\Application` instance:

```yaml
services:

  # Event

  event_hook.on_rescue_exception:
    class: App\Application\EventHook\RescueExceptionHandler
    arguments:
      - "@renderer"

  # Controller

  controller.page:
    class: App\Infrastructure\Controller\PageController
```




## tl;dr

Services can be defined using [Symfony's Dependency Injection component][] and only super sexy
services are bound to the `ICanBoogie\Application` instance.

You can check [icanboogie/service][] and [icanboogie/bind-symfony-dependency-injection][] packages
for more information on this amazing and exciting new feature.





[Symfony's Dependency Injection component]: https://symfony.com/doc/current/components/dependency_injection.html
[icanboogie/service]: https://github.com/ICanBoogie/Service
[icanboogie/bind-symfony-dependency-injection]: https://github.com/ICanBoogie/bind-symfony-dependency-injection
[the concept of bindings implemented in ICanBoogie]: https://icanboogie.org/docs/4.0/bindings
[message bus]: https://github.com/ICanBoogie/MessageBus
[prototype system]: https://icanboogie.org/docs/4.0/prototypes
[configuration files]: https://icanboogie.org/docs/4.0/configuration
[ICanBoogie]: https://icanboogie.org

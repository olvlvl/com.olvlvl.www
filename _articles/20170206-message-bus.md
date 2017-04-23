# A simple message bus

[icanboogie/message-bus][] provides a very simple message bus that can handle messages right away or
push them in a queue. Implemented with a functional approach, it tries to be as flexible as
possible: the message handler provider and the message pusher are defined with simple callables that
you can implement with your favorite resolver and your favorite message queue.

Few components make most of it: The `MessageBus` interface defines a unique `dispatch()` method;
`ShouldBePushed` should be implemented by messages that should be pushed in a queue rather than
handled by the application; finally the class `SimpleMessageBus` is a simple implementation of
`MessageBus`.

The following example demonstrates how to instantiate a message bus and dispatch a message:

```php
<?php

namespace ICanBoogie\MessageBus;

/* @var MessageHandlerProvider|callable $message_handler_provider */
/* @var MessagePusher|callable $message_pusher */

$bus = new SimpleMessageBus($message_handler_provider, $message_pusher);

/* @var Message $message */

// The message is handled right away by an handler
$result = $bus->dispatch($message);

/* @var ShouldBePushed $message */

// The message is pushed to a queue
$bus->dispatch($message);
```





## Message handler provider

The message handler provider is a callable with a signature similar to the
[MessageHandlerProvider][] interface, the package provides a simple message handler provider
that only requires an array of key/value pairs, where _key_ is a message class and _value_
a message handler callable.

The following example demonstrates how to define a message handler provider with a selection
of messages and their handlers:

```php
<?php

use App\Application\Message;
use ICanBoogie\MessageBus\SimpleMessageHandlerProvider;

$message_handler_provider = new SimpleMessageHandlerProvider([

	Message\CreateArticle::class => function (Message\CreateArticle $message) {

		// create an article

	},

	Message\DeleteArticle::class => function (Message\DeleteArticle $message) {

        // delete an article

    },

]);
```

Of course, if you're using the [icanboogie/service][] package, you can use service references
instead of callables (well, technically, they are also callables):

```php
<?php

use App\Application\Message;
use ICanBoogie\MessageBus\SimpleMessageHandlerProvider;

use function ICanBoogie\Service\ref;

$message_handler_provider = new SimpleMessageHandlerProvider([

	Message\CreateArticle::class => ref('handler.article.create'),
	Message\DeleteArticle::class => ref('handler.article.delete'),

]);
```





## tl;dr

I reinvented the wheel by creating a message bus, but saved the universe in the process.





[icanboogie/message-bus]:       https://github.com/ICanBoogie/MessageBus/
[icanboogie/service]:           https://github.com/ICanBoogie/Service
[MessageHandlerProvider]:       https://icanboogie.org/api/message-bus/master/class-ICanBoogie.MessageBus.MessageHandlerProvider.html

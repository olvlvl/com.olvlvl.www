# A simple message bus

[icanboogie/message-bus][] provides a very simple message bus that can handle messages right away or
push them in a queue. Implemented with a functional approach, it tries to be as flexible as
possible: the message handler provider and the message pusher are defined with simple callables that
you can implement with your favorite resolver and your favorite message queue.

Three interfaces and a class make most of it: `MessageBus` defines a unique `dispatch()` method;
`Message` should be implemented by messages to handle, while `MessageToPush` by message to push in a
queue; finally the class `SimpleMessageBus` is a simple implementation of `MessageBus`.

The following example demonstrates how to instantiate a message bus and dispatch messages:

```php
<?php

namespace ICanBoogie\MessageBus;

/* @var MessageHandlerProvider|callable $message_handler_provider */
/* @var MessagePusher|callable $message_pusher */

$bus = new SimpleMessageBus($message_handler_provider, $message_pusher);

/* @var Message $message */

// The message is handled right away by an handler
$result = $bus->dispatch($message);

/* @var MessageToPush $message */

// The message is pushed to a queue
$bus->dispatch($message);
```





## tl;dr

I reinvented the wheel by creating a message bus, but saved the universe in the process.





[icanboogie/message-bus]: https://github.com/ICanBoogie/MessageBus/

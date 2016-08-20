# Collecting and displaying localized error messages

After three years of relative quietness, I'm about to release v2.0 of my error collector package
[ICanBoogie/Errors][]; because after working on my validation package [ICanBoogie/Validate][], and
trying my best to implement it in [Brickrouge][] in the most uncoupled way possible, it was clear to
me that the decisions I made regarding error instances were not the best, namely localizing error
messages.

## A brief history

When I first designed the error collector, errors were defined as strings.

```php
<?php

use ICanBoogie\Errors;

$errors = new Errors;
$errors[] = "Generic error";
$errors['name'] = "Parameter `name` is required";
```

Later I used instances of `FormattedMessage`, than `I18n\FormattedMessage` to store localized
messages:

```php
<?php

use ICanBoogie\Errors;
use ICanBoogie\I18n\FormattedMessage;

$errors = new Errors;
$errors[] = new FormattedMessage("Generic error");
$errors['name'] = new FormattedMessage("Parameter `:param` is required, [ 'param' => 'name' ]);
```

Then I tried to make it easier by introducing the `add()` method, creating instances under the hood:

```php
<?php

use ICanBoogie\Errors;

$errors = new Errors;
$errors->add(null, "Generic error");
$errors->add('name', "Parameter `:param` is required, [ 'param' => 'name' ]);
```

Developing [ICanBoogie/Validate][] made me realize this was all a big mistake. I was storing data
far too complex, which made manipulating and displaying it not that easy in the end.





## Make it simpler and give me a kiss

[ICanBoogie/Validate][]'s error messages are super simple, and if you echo a collection of errors
about required attributes you might be surprised to read something like this:

```
is required
is required
is required
```

That's because these errors are ment to be formatted, and, most important, they are ment to be
translated.

You see, an error is not a string, it's actually an instance with `format` and `args` properties. It
looks something like this:

```
class ICanBoogie\Validate\Message {
	$format => "is required"
	$args => [
		'attribute' => "name"
	]
}	
```

Your translator would probably translate "is required" as "The attribute \`{attribute}\` is
required", then the message would get formatted as "The attribute \`name\` is required".

Formatting arguments can also be modified before the translated string is formatted. For instance,
when [Brickrouge][] renders it's errors it modifies the formatting arguments to add the label of the
element with a validation error, that is much nicer for the user than its machine name. "is
required" would get translated as "The field \`{label}\` is required", then the message would get
translated as "The field \`Name\` is required".

The error instance doesn't know that, and it shouldn't care, because in the ends what matter is what
displays the error, in which context.





## Conclusion

Error instances should be the simplest possible, using very generic and unspecific messages, so that
they could be easily consumed and manipulated, because when you create one, you have no idea how it
will be displayed to the user, in what language, in what context.

**tl;dr**: simple error messages + formatting arguments = ♥.





[Brickrouge]:                         https://github.com/Brickrouge/Brickrouge
[ICanBoogie/Errors]:                  https://github.com/ICanBoogie/Errors
[ICanBoogie/Validate]:                https://github.com/ICanBoogie/Validate

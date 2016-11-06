# ICanBoogie/CLDR v1.7 – Plurals

I just released [ICanBoogie/CLDR v1.7][] with support for plurals. A lot of work to find what pluralization rules a language use, or what pluralization rule should be applied to format a number. You see, depending on the language the rules may vary, some languages such English or French pluralize anything that is not `0` or `1`, but other languages like Arabic have more subtleties and will distinguish zero, one, two, few and many.

ICanBoogie's CLDR follows the [rules defined by the Unicode's CLDR](http://unicode.org/reports/tr35/tr35-numbers.html#Language_Plural_Rules), and makes it easy to find the plural rules for a language, or for a number:

```php
<?php

/* @var $repository ICanBoogie\CLDR\Repository */

$repository->plurals->rules_for('fr'); // [ 'one', 'other' ]
$repository->plurals->rules_for('ar'); // [ 'zero', 'one', 'two', 'few', 'many', 'other' ]

$repository->plurals->rule_for(1.5, 'fr'); // 'one'
$repository->plurals->rule_for(2, 'fr'); // 'other'
$repository->plurals->rule_for(2, 'ar'); // 'twe'
```

Now that this feature is implemented I'll be able to work on units formatting and maybe some other things.

If you wish to learn more about ICanBoogie's CLDR please [check its documentation](https://github.com/ICanBoogie/CLDR).

[ICanBoogie/CLDR v1.7]: https://github.com/ICanBoogie/CLDR/releases/tag/v1.7.0

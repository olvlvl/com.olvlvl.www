# ICanBoogie/CLDR v1.8 – Units

I just released [ICanBoogie/CLDR v1.8][] with support for units, another part of number formatting.
Quantities of units such as years, months, days, hours, minutes and seconds can be formatted— for
example, in English, "1 day" or "3 days".

<!-- body -->

It's easy to make use of this functionality via a locale's units:

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$units = $repository->locales['en']->units;
$units->duration_hour->name;                      // hours
$units->duration_hour->short_name;                // h
$units->duration_hour(1);                         // 1 hour
$units->duration_hour(23);                        // 23 hours
$units->duration_hour(23, $units::LENGTH_SHORT);  // 23 hr
$units->duration_hour(23, $units::LENGTH_NARROW); // 23h
```

[Many units are available](http://unicode.org/reports/tr35/tr35-general.html#Unit_Elements).

### Per unit

Combination of units, such as _miles per hour_ or _liters per second_, can be created. Some units
already have 'precomputed' forms, such as `kilometer_per_hour`; where such units exist, they should
be used in preference.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$units = $repository->locales['en']->units;
$units->volume_liter->per_unit(12.345, $units->duration_hour);
// 12.345 liters per hour
$units->volume_liter->per_unit(12.345, $units->duration_hour, $units::LENGTH_SHORT);
// 12.345 Lph
$units->volume_liter->per_unit(12.345, $units->duration_hour, $units::LENGTH_NARROW);
// 12.345l/h
```

### Units in composed sequence

Units may be used in composed sequences, such as **5° 30m** for 5 degrees 30 minutes, or **3 ft, 2
in**. For that purpose, the appropriate width can be used to compose the units in a sequence.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$units = $repository->locales['en']->units;

$units->sequence
	->angle_degree(5)
	->duration_minute(30)
	->as_narrow;
	// 5° 30m

$units->sequence
	->length_foot(3)
	->length_inch(2)
	->as_short;
	// 3 ft, 2 in

$units = $repository->locales['fr']->units;

$units->sequence
	->duration_hour(12)
	->duration_minute(34)
	->duration_second(45)
	->as_long;
	// 12 heures, 34 minutes et 56 secondes

$units->sequence
	->duration_hour(12)
	->duration_minute(34)
	->duration_second(45)
	->as_short;
	// 12 h, 34 min et 56 s

$units->sequence
	->duration_hour(12)
	->duration_minute(34)
	->duration_second(45)
	->as_narrow;
	// 12h 34m 56s
```





[ICanBoogie/CLDR v1.8]: https://github.com/ICanBoogie/CLDR

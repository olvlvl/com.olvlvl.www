# Using APC with Travis-CI

It's not easy getting APC to work on [Travis-CI][], every now and then something is updated and your
clever solution is broken again. What's super annoying is that you cannot even trust the
documentation. Right now it says that [APC is available for PHP
7](https://docs.travis-ci.com/user/languages/php#PHP-7.0), but when you try to activate the
extension a wild error message appears, saying the extension file is missing… Anyway, I searched the
internet and here the solution I found.





## Choosing the right extension, version

In order to have APC working for your PHP version, you actually need to install [apcu][], since
[apc][] has been abandoned in 2012. Also, you need to pick the right version, because v4.0 is
only working up to PHP 5.6, while v5.0 is only working from PHP 7.0:

```yaml
sudo: false

language: php

php:
  - 5.6
  - 7.0

before_install:
  - if [[ $TRAVIS_PHP_VERSION = "5.6" ]]; then echo yes | pecl install apcu-4.0.11; fi
  - if [[ $TRAVIS_PHP_VERSION = "7.0" ]]; then echo yes | pecl install apcu-5.1.5; fi
  - if [[ $TRAVIS_PHP_VERSION != "hhvm" ]]; then phpenv config-add .travis/travis-$TRAVIS_PHP_VERSION.ini; fi
```

> **Note**: As you can see, this is compatible with the new _container_ infrastructure.

Also, in a `.travis` folder (or whatever), add the corresponding files to enable the extension. For
PHP 5.6 you need to create a corresponding `travis-5.6.ini` file:

```ini
apc.enable_cli = 1
```

You can check my [Storage package's config][] for a real life example.





## Ariadne's thread

- https://github.com/travis-ci/travis-ci/issues/5618
- https://github.com/travis-ci/travis-ci/issues/5956
- https://github.com/nazar-pc/CleverStyle-Framework/blob/master/.travis.yml





[apc]:                        https://pecl.php.net/package/APC
[apcu]:                       https://pecl.php.net/package/APCu
[Storage package's config]:   https://github.com/ICanBoogie/Storage/blob/master/.travis.yml
[Travis-CI]:                  https://travis-ci.org/

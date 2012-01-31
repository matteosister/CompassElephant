# CompassElephant #

A wrapper for the compass binary written in PHP

Requirements
------------

- php >= 5.3
- *nix system with compass installed

Dependencies
------------

- [Symfony Finder](https://github.com/symfony/Finder)
- [Symfony Process](https://github.com/symfony/Process)

*for tests*

- [PHPUnit](https://github.com/sebastianbergmann/phpunit)

Installation
------------

**composer**

To install GitElephant with composer you simply need to create a *composer.json* in your project root and add:

``` json
{
    "require": {
        "cypresslab/gitelephant": ">=0.1.0"
    }
}
```

Then run

``` bash
$ wget -nc http://getcomposer.org/composer.phar
$ php composer.phar install
```

You have now GitElephant installed in *vendor/cypresslab/compasselephant*

And an handy autoload file to include in you project in *vendor/.composer/autoload.php*

**pear**

Add the Cypresslab channel

``` bash
$ pear channel-discover pear.cypresslab.net
```

And install the package. *By now CompassElephant is in alpha state. So remember the -alpha in the library name*

``` bash
$ pear install cypresslab/CompassElephant-alpha
```

On [Cypresslab pear channel homepage](http://pear.cypresslab.net/) you can find other useful information

Testing
-------

The library is fully tested with PHPUnit.

Go to the base library folder and run the test suites

``` bash
$ phpunit
```

Code style
----------

* GitElephant follows the [Symfony2 Coding Standard](https://github.com/opensky/Symfony2-coding-standard)
* I'm using [gitflow](https://github.com/nvie/gitflow) so, if you want to contribute, please send a pull-request on develop branch

How to use
----------

``` php
<?php

use CompassElephant\CompassProject,
    CompassElephant\CompassBinary;

$binary = new CompassBinary('/usr/local/bin/compass'); // this object represent your compass binary file on the filesystem
// you can omit the binary location. The library will try to find the binary with "which compass"
$binary = new CompassBinary();

$caller = new CommandCaller();

$project = new CompassProject();
```

Symfony2
--------

There is a [CompassElephantBundle](https://github.com/matteosister/CompassElephantBundle) to use this library inside a Symfony2 project.

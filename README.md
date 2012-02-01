# CompassElephant ![Travis build status](https://secure.travis-ci.org/matteosister/CompassElephant.png)#

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

To install CompassElephant with composer you simply need to create a *composer.json* in your project root and add:

``` json
{
    "require": {
        "cypresslab/compasselephant": ">=0.1.0"
    }
}
```

Then run

``` bash
$ wget -nc http://getcomposer.org/composer.phar
$ php composer.phar install
```

You have now CompassElephant installed in *vendor/cypresslab/compasselephant*

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

* CompassElephant follows the [Symfony2 Coding Standard](https://github.com/opensky/Symfony2-coding-standard)
* I'm using [gitflow](https://github.com/nvie/gitflow) so, if you want to contribute, please send a pull-request on develop branch

How to use
----------

Remember to **give the user the right permissions to access the filesystem**. If you are using a web server give permissions to both your user and the web server user.

**constructor**

``` php
<?php

$project = new CompassProject("/path/to/compass"); // create the base class, only the path is mandatory....
// Here is a full customized project
$path = "/path/to/compass";
$name = "blog"; // a project name. Not used in the library...but useful if you have more than one project
$binary = new CompassBinary('/usr/local/bin/compass'); // use this to set a custom path for the executable. If blank the library try with "which compass" before showing an error
$stalenessChecker = 'finder'; // or native. More on this later
$configFile = 'config_prod.rb'; // the name of the ruby config file. Defaults to config.rb
$autoInit = false; // if true, when given a folder without a config file inside, CompassElephant will try to initialize a compass project
$project = new CompassProject($path, $name, $binary, $stalenessChecker, $configFile, $autoInit);
// Here is the full constructor signature
/**
 * Class constructor
 *
 * @param string                              $projectPath      the path to the compass project
 * @param null                                $name             the project name
 * @param \CompassElephant\CompassBinary|null $compassBinary    a CompassBinary instance
 * @param mixed                               $stalenessChecker a StalenessCheckerInterface instance
 * @param string                              $configFile       the compass config file name
 * @param bool                                $autoInit         whether to call init() on an empty folder project
 *
 * @internal param \CompassElephant\CommandCaller $commandCaller a CommandCaller instance
 */
public function __construct($projectPath, $name = null, CompassBinary $compassBinary = null, $stalenessChecker = null, $configFile = 'config.rb', $autoInit = true) {
    // ...
}
```

**manage a compass project**

``` php
<?php

// if the project do not contains a config file, CompassElephant assumes it isn't initialized. See autoInit parameters for skip this step
if (!$project->isInitialized()) {
    $project->init(); // call the "compass create" command
}
// return false if the project needs to be recompiled. In other words if you changed something in config.rb, sass or scss files after the last sylesheets generation
if (!$project->isClean()) {
    $project->compile(); // compile the project
    echo $project->isClean(); // return true now
}
```

**Staleness Checker**

Compass checks if the project need to be compiled in two different ways:

*finder*
This method uses the awesome [Symfony Finder](https://github.com/symfony/Finder) component. It parse the config.rb file for the sass path and for the stylesheet path. Then check if the modification time of the stylesheets comes before the modification of the config file or a sass file. This is the default method.

*native*
uses the command "compass compile --dry-run" and parse the output to see if there are stylesheets not aligned with sass. This method is not so cool because it's really slow. Even by using it only in a dev environment, it adds 400-500 ms of overhead on every check. So **use it only if you can't use the finder method**

Are you reinventing the wheel?
------------------------------

I use [Assetic](https://github.com/kriswallsmith/assetic) for my assets...and you should use it too.

But for now the Compass implementation do not work as intended. Assetic is built upon the concept of a single asset compiled. And [it's not aware of dependencies](https://github.com/kriswallsmith/assetic/issues/79)

A pull request for assetic is not so easy, because it should change the entire structure of the library. And I know that the author is working on it.

I promise that, when the issue will be adressed, I will delete this library and return to Assetic. By now you can (should) use it to rewrite/uglify the stylesheets generated by CompassElephant. And this is exactly what I'm doing now.

Symfony2
--------

I'm working on a Symfony bundle. It's in the test phase...

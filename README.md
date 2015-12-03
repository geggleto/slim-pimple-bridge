# Slim-Pimple Bridge

The Slim-Pimple Bridge allows you to "bring your own"
[Pimple](http://pimple.sensiolabs.org/) 3.x container to a
[Slim](http://www.slimframework.com/) 3.x application without requiring you to
refactor your existing container.

## Installation

`composer require geggleto/slim-pimple-bridge`

## Usage

The example below assumes that your `Pimple\Container` instance is assigned to the
`$myAwesomePimpleContainer` variable.

``` php
// Here's a default \Slim\Container.
$slimContainer = new \Slim\Container();

// Use SlimPimpleBridge::merge() to add all of the services from your container
// to the $slimContainer instance.
$container = SlimPimpleBridge::merge(
    $slimContainer,
    $myAwesomePimpleContainer
);

// Done! It's that easy!
$app = new \Slim\App($container);
```

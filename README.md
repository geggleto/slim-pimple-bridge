# Slim-Pimple Bridge

Allows use of an existing Pimple 3.x container with Slim 3.x without rewriting
your container or adding the required Slim services.

## Installation

`composer require geggleto/slim-pimple-bridge`

## Usage

``` php
// Assumes $yourExistingContainer is an instance of Pimple\Container

$slimContainer = new SlimContainer();

// $container is $slimContainer, which now includes all services defined on $myExistingContainer
$container = SlimPimpleBridge::merge(
    $slimContainer,
    $myExistingContainer
);

$app = new \Slim\App($container);
```

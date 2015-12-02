<?php

namespace Geggleto\Test;

use Geggleto\SlimPimpleBridge;
use Pimple\Container as PimpleContainer;
use Slim\Container as SlimContainer;

class SlimPimpleBridgeTest extends \PHPUnit_Framework_TestCase
{
    public function testSlimPimpleBridge()
    {
        // Represents an existing container that needs to be integrated into
        // a Slim\Container
        $myExistingPimpleContainer = new PimpleContainer();
        // Define some services
        $myExistingPimpleContainer['test'] = function ($c) {
            return 'TEST';
        };

        $slimContainer = new SlimContainer();

        // Now merge the two containers!
        $container = SlimPimpleBridge::merge(
            $slimContainer,
            $myExistingPimpleContainer
        );

        $this->assertSame($slimContainer, $container);
        $this->assertInstanceOf('Slim\Handlers\Error', $container->get('errorHandler'));
        $this->assertEquals('TEST', $container->get('test'));
    }
}

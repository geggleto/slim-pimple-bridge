<?php

namespace Geggleto\Test;

use Geggleto\SlimPimpleBridge;
use Slim\Container as SlimContainer;
use Pimple\Container as PimpleContainer;

class SlimPimpleBridgeTest extends \PHPUnit_Framework_TestCase
{
    public function testSlimPimpleBridge()
    {
        // Represents an existing container that needs to be integrated into
        // the Slim\Container
        $myExistingPimpleContainer = new PimpleContainer();
        $myExistingPimpleContainer['test'] = function ($c) {
            return 'TEST';
        };

        $bridge = new SlimPimpleBridge($myExistingPimpleContainer);

        $slimContainer = new SlimContainer();

        // Uses the SlimPimpleBridge to add existing services to the SlimContainer
        $slimContainer->register($bridge);

        $this->assertEquals('TEST', $slimContainer->get('test'));
    }
}

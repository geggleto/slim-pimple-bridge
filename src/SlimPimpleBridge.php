<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2015-12-01
 * Time: 12:54 PM.
 */
namespace Geggleto;

use Pimple\Container as PimpleContainer;
use Pimple\ServiceProviderInterface;

final class SlimPimpleBridge implements ServiceProviderInterface
{
    /**
     * Public constructor.
     *
     * @param PimpleContainer $container Your existing Pimple container
     */
    public function __construct(PimpleContainer $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     *
     * @see http://pimple.sensiolabs.org/#extending-a-container
     */
    public function register(PimpleContainer $pimple)
    {
        foreach ($this->container->keys() as $key) {
            $pimple[$key] = $this->container->raw($key);
        }
    }
}

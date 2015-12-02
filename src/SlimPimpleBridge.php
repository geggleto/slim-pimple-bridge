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
use Slim\Container as SlimContainer;

final class SlimPimpleBridge implements ServiceProviderInterface
{
    /**
     * @var PimpleContainer
     */
    private $pimpleContainer;

    /**
     * Private constructor.
     *
     * @param PimpleContainer $pimpleContainer
     */
    private function __construct(PimpleContainer $pimpleContainer)
    {
        $this->pimpleContainer = $pimpleContainer;
    }

    /**
     * Merge a Pimple\Container with a Slim\Container.
     *
     * @param SlimContainer   $slimContainer
     * @param PimpleContainer $pimpleContainer
     *
     * @return SlimContainer The original $slimContainer, which now includes
     *                       all of the services from the $pimpleContainer
     */
    public static function merge(SlimContainer $slimContainer, PimpleContainer $pimpleContainer)
    {
        $slimContainer->register(new self($pimpleContainer));

        return $slimContainer;
    }

    /**
     * {@inheritDoc}
     */
    public function register(PimpleContainer $slimContainer)
    {
        foreach ($this->pimpleContainer->keys() as $key) {
            $slimContainer[$key] = $this->pimpleContainer->raw($key);
        }
    }
}

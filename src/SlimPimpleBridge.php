<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2015-12-01
 * Time: 12:54 PM
 */

namespace Geggleto;

use Pimple\Container as PimpleContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\CallableResolver;
use Slim\Collection;
use Slim\Handlers\Error;
use Slim\Handlers\NotFound;
use Slim\Handlers\NotAllowed;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\Http\EnvironmentInterface;
use Slim\Interfaces\InvocationStrategyInterface;
use Slim\Interfaces\RouterInterface;
use Slim\Router;


class SlimPimpleBridge
{
    private $defaultSettings = [
        'httpVersion' => '1.1',
        'responseChunkSize' => 4096,
        'outputBuffering' => 'append',
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,
    ];

    public function registerSlimServices(PimpleContainer $pimpleContainer, $settings = [])
    {
        $defaultSettings = $this->defaultSettings;
        /**
         * This service MUST return an array or an
         * instance of \ArrayAccess.
         *
         * @return array|\ArrayAccess
         */
        $pimpleContainer['settings'] = function () use ($userSettings, $defaultSettings) {
            return new Collection(array_merge($defaultSettings, $userSettings));
        };
        if (!isset($pimpleContainer['environment'])) {
            /**
             * This service MUST return a shared instance
             * of \Slim\Interfaces\Http\EnvironmentInterface.
             *
             * @return EnvironmentInterface
             */
            $pimpleContainer['environment'] = function () {
                return new Environment($_SERVER);
            };
        }
        if (!isset($pimpleContainer['request'])) {
            /**
             * PSR-7 Request object
             *
             * @param Container $c
             *
             * @return ServerRequestInterface
             */
            $pimpleContainer['request'] = function ($c) {
                return Request::createFromEnvironment($c->get('environment'));
            };
        }
        if (!isset($pimpleContainer['response'])) {
            /**
             * PSR-7 Response object
             *
             * @param Container $c
             *
             * @return ResponseInterface
             */
            $pimpleContainer['response'] = function ($c) {
                $headers = new Headers(['Content-Type' => 'text/html; charset=UTF-8']);
                $response = new Response(200, $headers);
                return $response->withProtocolVersion($c->get('settings')['httpVersion']);
            };
        }
        if (!isset($pimpleContainer['router'])) {
            /**
             * This service MUST return a SHARED instance
             * of \Slim\Interfaces\RouterInterface.
             *
             * @return RouterInterface
             */
            $pimpleContainer['router'] = function () {
                return new Router;
            };
        }
        if (!isset($pimpleContainer['foundHandler'])) {
            /**
             * This service MUST return a SHARED instance
             * of \Slim\Interfaces\InvocationStrategyInterface.
             *
             * @return InvocationStrategyInterface
             */
            $pimpleContainer['foundHandler'] = function () {
                return new RequestResponse;
            };
        }
        if (!isset($pimpleContainer['errorHandler'])) {
            /**
             * This service MUST return a callable
             * that accepts three arguments:
             *
             * 1. Instance of \Psr\Http\Message\ServerRequestInterface
             * 2. Instance of \Psr\Http\Message\ResponseInterface
             * 3. Instance of \Exception
             *
             * The callable MUST return an instance of
             * \Psr\Http\Message\ResponseInterface.
             *
             * @param Container $c
             *
             * @return callable
             */
            $pimpleContainer['errorHandler'] = function ($c) {
                return new Error($c->get('settings')['displayErrorDetails']);
            };
        }
        if (!isset($pimpleContainer['notFoundHandler'])) {
            /**
             * This service MUST return a callable
             * that accepts two arguments:
             *
             * 1. Instance of \Psr\Http\Message\ServerRequestInterface
             * 2. Instance of \Psr\Http\Message\ResponseInterface
             *
             * The callable MUST return an instance of
             * \Psr\Http\Message\ResponseInterface.
             *
             * @return callable
             */
            $pimpleContainer['notFoundHandler'] = function () {
                return new NotFound;
            };
        }
        if (!isset($pimpleContainer['notAllowedHandler'])) {
            /**
             * This service MUST return a callable
             * that accepts three arguments:
             *
             * 1. Instance of \Psr\Http\Message\ServerRequestInterface
             * 2. Instance of \Psr\Http\Message\ResponseInterface
             * 3. Array of allowed HTTP methods
             *
             * The callable MUST return an instance of
             * \Psr\Http\Message\ResponseInterface.
             *
             * @return callable
             */
            $pimpleContainer['notAllowedHandler'] = function () {
                return new NotAllowed;
            };
        }
        if (!isset($pimpleContainer['callableResolver'])) {
            /**
             * Instance of \Slim\Interfaces\CallableResolverInterface
             *
             * @param Container $c
             *
             * @return CallableResolverInterface
             */
            $pimpleContainer['callableResolver'] = function ($c) {
                return new CallableResolver($c);
            };
        }

        return $pimpleContainer;
    }
}
<?php

namespace Mindk\Framework;

use Mindk\Framework\Request\Request;
use Mindk\Framework\Router\Exception\RouteNotFoundException;
use Mindk\Framework\Router\Router;
use Mindk\Framework\Response\Response;

/**
 * Class Application
 * Mindk Framework application instance class
 *
 * @package Mindk\Framework
 */
class Application
{
    /**
     * @var array App config
     */
    public $config = [];

    /**
     * Application initialization
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * Process the request
     */
    public function run()
    {
        $router = new Router($this->config['routes']);

        try {
            $route = $router->getRoute(Request::getRequest());

            $route_controller = $route->getController();
            $route_method = $route->getMethod();
            if (class_exists($route_controller)) {
                $reflectionClass = new \ReflectionClass($route_controller);
                if ($reflectionClass->hasMethod($route_method)) {
                    $controller = $reflectionClass->newInstance();
                    $reflectionMethod = $reflectionClass->getMethod($route_method);
                    $response = $reflectionMethod->invokeArgs($controller, $route->getParams());

                    if ($response instanceof Response) {
                        $response->send();
                    }
                }
            }
        } catch (RouteNotFoundException $e) {
            //@TODO: redirect to 404
            echo "Route was not found";
        } catch (\Exception $e) {
            //@TODO:
            echo "Smth went wrong...";
        }

    }

    /**
     * Application run postflight
     */
    public function __destruct()
    {
        //@TODO: Close all...
    }
}
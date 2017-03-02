<?php

namespace Mindk\Framework;

use Mindk\Framework\Request\Request;
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
        $request = Request::getRequest();

        try {
            $route = $router->getRoute($request);

            if(class_exists($route->controller)){
                $reflectionClass = new \ReflectionClass($route->controller);
                if($reflectionClass->hasMethod($route->method)){
                    $controller = $reflectionClass->newInstance();
                    $reflectionMethod = $reflectionClass->getMethod($route->method);

                    $response = $reflectionMethod->invokeArgs($controller, $route->params);

                    if($response instanceof Response){
                        $response->send();
                    }
                }
            }
        } catch(\Exception $e) {
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
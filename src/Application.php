<?php

namespace Mindk\Framework;

use Mindk\Framework\Request\Request;
use Mindk\Framework\Router\Router;

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
        $route = $router->getRoute($request);

        echo "<pre>";
        print_r($route);
        echo "</pre>";
    }

    /**
     * Application run postflight
     */
    public function __destruct()
    {
        //@TODO: Close all...
    }
}
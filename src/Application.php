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
        echo "<pre>";
        print_r($this->config);
        echo "</pre>";
    }

    /**
     * Application run postflight
     */
    public function __destruct()
    {
        echo 'Done!';
    }
}
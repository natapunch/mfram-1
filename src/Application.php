<?php

namespace Mindk\Framework;
use Mindk\Framework\Request\Request;
use Mindk\Framework\Router\Router;

/**
 * Class Application
 * @package Mindk\Framework
 */
class Application
{
    public function start()
    {
        $request = Request::getRequest();

//        debug($request->getUri());
//        debug($request->getMethod());
//        debug($_GET);

        $router = new Router(require __DIR__ . "/../config/routes.php");



    }

}
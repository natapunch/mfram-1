<?php

namespace Mindk\Framework\Request;

class Request
{
    private static $request = null;

    /**
     * Request constructor.
     */
    private function __construct()
    {
    }

    /**
     * Returns request
     *
     * @return Request
     */
    public static function getRequest(): self
    {
        if (!self::$request) {
            self::$request = new self();
        }

        return self::$request;
    }

    public function getUri():string
    {
//        debug($_SERVER);

        return $_SERVER["REQUEST_URI"];
    }

    public function getMethod(): string
    {
        return $_SERVER["REQUEST_METHOD"];
    }
}
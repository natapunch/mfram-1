<?php

namespace Mindk\Framework\Request;

/**
 * Class Request
 * Request processing class
 *
 * @package Mindk\Framework
 */
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

    /**
     * Get current URI
     *
     * @return string
     */
    public function getUri(): string
    {
        return $_SERVER["REQUEST_URI"];
    }

    /**
     * Get current request method
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $_SERVER["REQUEST_METHOD"];
    }
}
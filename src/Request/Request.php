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
    /**
     * @var null    Request instance
     */
    private static $request = null;

    /**
     * @var array   HTTP request headers
     */
    protected $headers = [];

    /**
     * Request constructor.
     */
    private function __construct()
    {
        // Retrieve request headers:

        $headers = [];

        if(function_exists('getallheaders')){
            $headers = getallheaders();
        } else {
            foreach($_SERVER as $key => $value) {
                if ( substr($key,0,5) == "HTTP_" ) {
                    $key = str_replace(" ", "-", ucwords(strtolower(str_replace("_"," ",substr($key,5)))));
                    $headers[$key] = $value;
                }else{
                    $headers[$key] = $value;
                }
            }
        }

        $this->headers = $headers;
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
        $raw = $_SERVER["REQUEST_URI"];
        $buffer = explode('?', $raw);

        return array_shift($buffer);
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

    /**
     * Get request header value
     *
     * @param null $key All headers will be returned if key not specified
     *
     * @return mixed
     */
    public function getHeader($key = null)
    {
        if(empty($key)){
            return $this->headers;
        }

        return isset($this->headers[$key]) ? $this->headers[$key] : null;
    }

    /**
     * Get request var
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if(preg_match('/^get([\w\d_]*)/', $method, $match)){
            $filter = @strtolower($match[1]);
            $param = array_shift($args);
            $default = array_shift($args);
            $raw = isset($_REQUEST[$param]) ? $_REQUEST[$param] : $default;

            switch($filter){
                case 'raw': $filtered = $raw; break;
                case 'int': $filtered = (int)$raw; break;
                case 'float': $filtered = (float)$raw; break;
                case 'string':
                default: $filtered = preg_replace('/[^\s\w\d_\-\.\,\+\(\)]*/Ui', '', urldecode($raw));
            }

            return $filtered;
        }
    }

    public function __get($name)
    {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : null;
    }

    public function __isset($name)
    {
        return isset($_REQUEST[$name]);
    }
}
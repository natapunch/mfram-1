<?php

namespace Mindk\Framework\Router;

use Mindk\Framework\Request\Request;
use Mindk\Framework\Router\Exception\InvalidRouteNameException;
use Mindk\Framework\Router\Exception\RouteKeyNotPassedException;
use Mindk\Framework\Router\Exception\RouteNotFoundException;

/**
 * Class Router
 * Routing processing class
 *
 * @package Mindk\Framework\Router
 */
class Router
{

    const DEFAULT_VAR_REGEXP = "[^\/]+";

    /**
     * @var array   Routing map
     */
    private $routes = [];

    /**
     * Router constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {

            $existed_variables = $this->getExistedVariables($value);

            $this->routes[$key] = [
                "origin" => $value["pattern"],
                "regexp" => $this->getRegexpFromRoute($value, $existed_variables),
                "method" => isset($value["method"]) ? $value["method"] : "GET",
                "controller_name" => $this->getControllerName($value),
                "controller_method" => $this->getControllerMethod($value),
                "variables" => $existed_variables
            ];
        }
    }

    /**
     * Get current route object
     *
     * @param Request $request
     * @return Route
     * @throws RouteNotFoundException
     */
    public function getRoute(Request $request): Route
    {
        $uri = $request->getUri();

        foreach ($this->routes as $name => $route) {
            if (preg_match_all($route['regexp'], $uri, $matches) && ($route['method'] == $request->getMethod())) {
                $result = new Route($name, $route['controller_name'], $route['controller_method']);

                if (!empty($route['variables'])) {
                    array_shift($matches);
                    $result->setParams($this->parseParamValues($route['variables'], $matches));
                }

                return $result;
            }
        }

        throw new RouteNotFoundException('Route not found');
    }

    /**
     * Returns name of controller
     *
     * @param array $config_route
     * @return string
     */
    private function getControllerName(array $config_route): string
    {
        return explode("@", $config_route["action"])[0];

    }

    /**
     * Return name of controller method
     *
     * @param array $config_route
     * @return string
     */
    private function getControllerMethod(array $config_route): string
    {
        return explode("@", $config_route["action"])[1];
    }

    /**
     * Returns regexp by config
     *
     * @param array $config_route
     * @param array $existed_variables
     * @return string
     */
    private function getRegexpFromRoute(array $config_route, array $existed_variables): string
    {
        $result = str_replace("/", "\/", $config_route["pattern"]);

        $variables_configs = isset($config_route["variables"]) ? $config_route["variables"] : [];
        for ($i = 0; $i < count($existed_variables); $i++) {
            $var_reg = "(" .
                (array_key_exists($existed_variables[$i], $variables_configs)
                    ? $config_route["variables"][$existed_variables[$i]]
                    : self::DEFAULT_VAR_REGEXP
                )
                . ")";
            $result = str_replace("{" . $existed_variables[$i] . "}", $var_reg, $result);

        }

        return "/^" . $result . "$/";
    }


    /**
     * Returns all variables that exist in pattern
     *
     * @param $config
     * @return array
     */
    private function getExistedVariables($config)
    {
        preg_match_all("/{.+}/U", $config["pattern"], $variables);

        return array_map(function ($value) {
            return substr($value, 1, strlen($value) - 2);
        }, $variables[0]);
    }

    /**
     * Bind param values to assoc array
     *
     * @param $variables
     * @param $values
     *
     * @return array
     */
    private function parseParamValues($variables, $values)
    {
        $buffer = array_map(function ($item) {
            return is_array($item) ? array_shift($item) : $item;
        }, $values);

        return array_combine($variables, $buffer);
    }

    /**
     * Build link
     *
     * @param string $route_name
     * @param array $params
     * @return string
     * @throws InvalidRouteNameException if route name does not exist
     * @throws RouteKeyNotPassedException if any key was not passed to params array
     */
    public function getLink(string $route_name, array $params = []): string
    {
        if (array_key_exists($route_name, $this->routes)) {
            preg_match_all("/\{([\w\d_]+)\}/", $link = $this->routes[$route_name]['origin'], $keys);

            foreach ($keys[1] as $key) {
                if (!array_key_exists($key, $params)) {
                    throw new RouteKeyNotPassedException("Key \"$key\" is required for route \"$route_name\"");
                } else {
                    $link = str_replace("{" . $key . "}", $params[$key], $link);
                }
            }
        } else {
            throw new InvalidRouteNameException("Route with name \"$route_name\" was not found in config");
        }

        return $link;
    }
}
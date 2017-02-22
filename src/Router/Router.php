<?php

namespace Mindk\Framework\Router;

use Mindk\Framework\Request\Request;

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
     */
    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {

            $existed_variables = $this->getExistedVariables($value);

            $this->routes[$key] = [
                "regexp" => $this->getRegexpFromRoute($value, $existed_variables),
                "method" => isset($value["method"]) ? $value["method"] : "GET",
                "controller_name" => $this->getControllerName($value),
                "controller_method" => $this->getControllerMethod($value),
                "variables" => $existed_variables
            ];
        }

        debug($this->routes);

    }

    /**
     * Get current route object
     *
     * @param Request $request
     * @return Route
     */
    public function getRoute(Request $request): Route
    {
        $uri = $request->getUri();

        foreach($this->routes as $name => $route){
            if(preg_match('/'.$route['regexp'].'/', $uri) && ($route['method'] == $request->getMethod())){
                $result = new Route();
                $result->name = $name;
                $result->controller = $route['controller_name'];
                $result->method = $route['controller_method'];
                // ...

                return $result;
            }
        }

        throw new \Exception('Route not found');
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
     * @return string
     */
    private function getRegexpFromRoute(array $config_route, array $existed_variables): string
    {
        $pattern = $config_route["pattern"];
        $result = str_replace("/", "\/", $pattern);


        $variables_names = $existed_variables;

        for ($i = 0; $i < count($variables_names); $i++) {
            $var_reg = "(" .
                (array_key_exists($variables_names[$i], $config_route["variables"])
                    ? $config_route["variables"][$variables_names[$i]]
                    : self::DEFAULT_VAR_REGEXP
                )
                . ")";
            $result = str_replace("{" . $variables_names[$i] . "}", $var_reg, $result);

        }

        return "^" . $result . "$";
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
}
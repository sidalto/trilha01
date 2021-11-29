<?php

namespace App\Routes;

use App\Http\Request;
use Closure;

class Router
{
    private static array $routeList;

    public function get(string $route, $handle): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $uri = (empty($_GET) ? "/" : $_GET["url"]);
            $params = '';

            if ($uri != "/") {
                $uri = explode("/", $uri);
                if (count($uri) > 1) {
                    $params = end($uri);
                }
                $uri = "/" . array_shift($uri);
            }

            $controller = (!is_string($handle) ? $handle : strstr($handle, "@", true));
            $method = (!is_string($handle)) ?: str_replace("@", "", strstr($handle, "@", false));
            self::$routeList = [
                $route => [
                    "route" => $route,
                    "controller" => $controller,
                    "method" => $method,
                    "params" => $params
                ]
            ];

            self::dispatch($uri);
        }
    }

    public function post(string $route, $handle): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $params = $_POST;
            $uri = $route;

            $controller = (!is_string($handle) ? $handle : strstr($handle, "@", true));
            $method = (!is_string($handle)) ?: str_replace("@", "", strstr($handle, "@", false));

            self::$routeList = [
                $route => [
                    "route" => $route,
                    "controller" => $controller,
                    "method" => $method,
                    "params" => $params
                ]
            ];

            self::dispatch($uri);
        }
    }

    public function put(string $route, $handle): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "PUT") {
            parse_str(file_get_contents('php://input'), $data);
            $uri = $_SERVER['REQUEST_URI'];
            $uri = explode("/", $uri);
            $uri = end($uri);
            $data['args'] = $uri;

            $controller = (!is_string($handle) ? $handle : strstr($handle, "@", true));
            $method = (!is_string($handle)) ?: str_replace("@", "", strstr($handle, "@", false));

            self::$routeList = [
                $route => [
                    "route" => $route,
                    "controller" => $controller,
                    "method" => $method,
                    "params" => $data
                ]
            ];

            self::dispatch($route);
        }
    }

    public function delete(string $route, $handle): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
            $uri = $_SERVER['REQUEST_URI'];
            $uri = explode("/", $uri);
            $uri = end($uri);

            $controller = (!is_string($handle) ? $handle : strstr($handle, "@", true));
            $method = (!is_string($handle)) ?: str_replace("@", "", strstr($handle, "@", false));
            self::$routeList = [
                $route => [
                    "route" => $route,
                    "controller" => $controller,
                    "method" => $method,
                    "params" => $uri
                ]
            ];

            self::dispatch($route);
        }
    }

    public static function dispatch(string $route)
    {
        $route = (array_key_exists($route, self::$routeList) ? self::$routeList[$route] : '');

        if (!empty($route)) {
            $params = ($route['params']) ?? [];

            if ($route['controller'] instanceof \Closure) {
                call_user_func($route['controller'], $params);
                return;
            }

            $controller = (!empty($route['controller']) ? self::namespace() . $route['controller'] : '');
            $method = ($route['method']) ?? '';

            if (class_exists($controller)) {
                $newController = new $controller;
                if (method_exists($controller, $method)) {
                    call_user_func(array($newController, $method), $params);
                    return;
                }
            }
        }
    }

    private static function namespace(): string
    {
        return "App\Controllers\\";
    }
}

<?php

namespace App\Routes;

use App\Http\Request;
use Exception;

class Router
{
    private Request $request;
    private static $routeList = [
        'GET' => [
            '/customer',
            '/company',
            '/account'
        ],
        'POST' => [
            '/customer',
            '/company',
            '/account'
        ],
        'PUT' => [
            '/customer',
            '/company',
            '/account'
        ],
        'DELETE' => [
            '/customer',
            '/company',
            '/account'
        ],
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(): ?array
    {
        $params = '';
        $route = explode("/", $this->request->getQueryParams()['url']);

        if (count($route) > 1) {
            $params = $route[1];
        }

        $action = $this->namespace() . ucfirst($route[0]) . "Controller";
        $route = "/" . $route[0];
        $httpMethod = $this->request->getHttpMethod();
        $pos = array_search($route, self::$routeList[$httpMethod]);

        if ($pos === false) {
            return null;
        }

        $route = self::$routeList[$httpMethod][$pos];

        return [
            'route' => $route,
            'action' => $action,
            'params' => $params
        ];
    }

    public function execute(string $controller, string $method, array $params)
    {
        if (class_exists($controller)) {
            $newController = new $controller;
            if (method_exists($newController, $method)) {
                call_user_func(array($newController, $method), $params);
                return;
            }
        }
    }

    public function dispatch()
    {
        $route = $this->handle();

        if (!$route) {
            return null;
        }

        $controller = $route['action'];

        switch ($this->request->getHttpMethod()) {
            case 'GET':
                $method = empty($route['params']) ? 'index' : 'getById';
                $this->execute($controller, $method, $route);
                break;
            case 'POST':
                $this->execute($controller, 'create', $this->request->getPostData());
                break;
            case 'PUT':
                $params['id'] = $route['params'];
                parse_str(file_get_contents("php://input"), $params['data']);
                $this->execute($controller, 'update', $params);
                break;
            case 'DELETE':
                $this->execute($controller, 'delete', $route);
                break;
            default:
                throw new Exception('Invalid HTTP Method');
                break;
        }
    }

    private static function namespace(): string
    {
        return "App\Controllers\\";
    }
}

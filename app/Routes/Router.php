<?php

namespace App\Routes;

use Exception;
use Pecee\SimpleRouter\SimpleRouter;
use Pecee\SimpleRouter\Exceptions\HttpException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\Http\Middleware\Exceptions\TokenMismatchException;

class Router extends SimpleRouter
{
    /**
     * @throws Exception
     * @throws TokenMismatchException
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public static function start(): void
    {
        require_once __DIR__ . '/../Helpers/helpers.php';
        require_once 'routes.php';

        parent::start();
    }
}

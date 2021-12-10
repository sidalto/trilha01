<?php

namespace App\Routes;

use Exception;
use DomainException;
use Pecee\SimpleRouter\SimpleRouter;
use Pecee\SimpleRouter\Exceptions\HttpException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\Http\Middleware\Exceptions\TokenMismatchException;

class Router extends SimpleRouter
{
    /**
     * @throws TokenMismatchException
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public static function start(): void
    {
        try {
            require_once __DIR__ . '/../Helpers/helpers.php';
            require_once 'routes.php';
            parent::start();
        } catch (TokenMismatchException $e) {
            throw new TokenMismatchException('Erro ao localizar o token');
        } catch (HttpException $e) {
            throw new HttpException('Endereço inválido');
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException('Endereço não encontrado');
        } catch (Exception $e) {
            throw new Exception('Token Inválido');
        }
    }
}

<?php

namespace App\Routes;

use Pecee\SimpleRouter\SimpleRouter;
use Pecee\SimpleRouter\Exceptions\HttpException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Exception;

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
            throw new Exception('Erro ao localizar o token');
        } catch (HttpException $e) {
            throw new HttpException('Erro ao processar a requisição');
        } catch (NotFoundHttpException $e) {
            throw new HttpException('Endereço não encontrado');
        } finally {
            throw new Exception('Erro interno do serviço');
        }
    }
}

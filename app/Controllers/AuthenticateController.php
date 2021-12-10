<?php

namespace App\Controllers;

use Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Auth\Authenticate;
use function App\Helpers\input;
use function App\Helpers\response;

class AuthenticateController
{
    public function index()
    {
        $handler = new StreamHandler(__DIR__ . '/../Logs/system.log', Logger::DEBUG);
        $logger = new Logger('wjcrypto-log');
        $logger->pushHandler($handler);

        try {
            $authenticate = new Authenticate();
            $email = input('email');
            $password = input('password');
            $token = $authenticate->authenticate($email, $password);
            $logger->info("Login efetuado pelo usuário: " . $email);

            return response()
                ->httpCode(200)
                ->json([
                    'message' => 'Success',
                    'data' => $token
                ]);
        } catch (Exception $e) {
            $logger->info("Falha no Login efetuado pelo usuário: " . $email);
            response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage()
                ]);
        }
    }
}

<?php

namespace App\Controllers;

use App\Auth\Authenticate;
use Exception;

use function App\Helpers\input;
use function App\Helpers\response;

class AuthenticateController
{
    public function index()
    {
        try {
            $authenticate = new Authenticate();
            $email = input('email');
            $password = input('password');
            $token = $authenticate->authenticate($email, $password);

            return response()
                ->httpCode(200)
                ->json([
                    'message' => 'Success',
                    'data' => $token
                ]);
        } catch (Exception $e) {
            response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage()
                ]);
        }
    }
}

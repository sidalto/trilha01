<?php

namespace App\Controllers;

use App\Auth\Authenticate;
use function App\Helpers\input;
use function App\Helpers\response;

class AuthenticateController
{
    public function index()
    {
        $authenticate = new Authenticate();
        $email = input('email');
        $password = input('password');
        $token = $authenticate->authenticate($email, $password);

        if (!$token) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => 'Invalid credentials',
                    'data' => []
                ]);
        }

        return response()
            ->httpCode(200)
            ->json([
                'message' => 'Success',
                'data' => $token
            ]);
    }
}

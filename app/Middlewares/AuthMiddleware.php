<?php

namespace App\Middlewares;

use App\Auth\Authenticate;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

use function App\Helpers\response;

class AuthMiddleware implements IMiddleware
{
    public function handle(Request $request): void
    {
        $authenticate = new Authenticate();
        $request->token = $authenticate->verifyAuth();

        if (!$request->token) {
            response()
                ->httpCode(400)
                ->json([
                    'message' => 'Invalid token'
                ]);
        }
    }
}

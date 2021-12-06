<?php

namespace App\Middlewares;

use App\Auth\Authenticate;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

use function App\Helpers\request;
use function App\Helpers\response;

class AuthMiddleware implements IMiddleware
{
    public function handle(Request $request): void
    {
        $authenticate = new Authenticate();
        $request->data = $authenticate->verifyAuth();

        if (!$request->data) {
            response()
                ->httpCode(400)
                ->json([
                    'message' => 'Invalid token'
                ]);
        }
    }
}

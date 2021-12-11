<?php

namespace App\Middlewares;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Auth\Authenticate;
use Exception;

use function App\Helpers\response;

class AuthMiddleware implements IMiddleware
{
    public function handle(Request $request): void
    {
        try {
            $authenticate = new Authenticate();
            $request->data = $authenticate->verifyAuth();
        } catch (Exception $e) {
            response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage()
                ]);
        }
    }
}

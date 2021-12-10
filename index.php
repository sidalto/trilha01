<?php

require_once __DIR__ . "/vendor/autoload.php";

use App\Routes\Router;
use function App\Helpers\response;

try {
    Router::setDefaultNamespace("\App\Controllers");
    Router::start();
} catch (\Exception $e) {
    return response()
        ->httpCode(400)
        ->json([
            'message' => $e->getMessage(),
        ]);
}

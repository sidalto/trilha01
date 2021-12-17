<?php

require_once __DIR__ . "/vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Routes\Router;
use function App\Helpers\response;

$handler = new StreamHandler(__DIR__ . '/app/Logs/system.log', Logger::DEBUG);
$logger = new Logger('wjcrypto-log');
$logger->pushHandler($handler);

try {
    Router::setDefaultNamespace("\App\Controllers");
    Router::init($logger);
} catch (\Exception $e) {
    $logger->notice($e->getMessage());

    return response()
        ->httpCode(400)
        ->json([
            'message' => $e->getMessage(),
        ]);
}

<?php

require_once __DIR__ . "/vendor/autoload.php";

use App\Routes\Router;

try {
    Router::setDefaultNamespace("\App\Controllers");
    Router::start();
} catch (\Exception $e) {
    return $e->getMessage();
}

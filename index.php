<?php

require_once __DIR__ . "/vendor/autoload.php";

use App\Routes\Router;

Router::setDefaultNamespace("\App\Controllers");
Router::start();

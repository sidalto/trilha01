<?php

require_once 'vendor/autoload.php';

use App\Database\MysqlConnection;

$connection = MysqlConnection::getInstance();

var_dump($connection);
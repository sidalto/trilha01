<?php

require_once 'vendor/autoload.php';

use App\Database\MysqlConnection;

$connection = MysqlConnection::getInstance();

$exec = "INSERT INTO clients (person_name, address, telephone) VALUES ('Sidalto', 'Rua 1', '(11)1111-1111')";

$result = $connection->exec($exec);

var_dump($result);
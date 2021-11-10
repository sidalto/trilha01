<?php

namespace App\Database;

use PDO;
use PDOException;

interface DatabaseInterface
{
    public static function getInstance(): PDO|PDOException;
}
<?php

namespace Trilha01\Database;

use PDO;
use PDOException;

class Connection
{
    private const HOST = "localhost";
    private const DB_NAME = "wjcrypto";
    private const USER = "root";
    private const PASSWORD = "triger";
    private const OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ];

    private static PDO $instance;

    final private function __construct()
    {
    }

    /** @return PDO|PDOException */
    final public function getInstance(): PDO
    {
        try {
            if (empty(self::$instance)) {
                self::$instance = new PDO(
                    "mysql:host=" . self::HOST . ";dbname=" . self::DB_NAME,
                    self::USER,
                    self::PASSWORD,
                    self::OPTIONS
                );
            }

            return self::$instance;
        } catch (PDOException $exception) {
            return $exception;
        }
    }
}
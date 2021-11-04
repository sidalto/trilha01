<?php

namespace App\Database;

use PDO;
use PDOException;
use App\Database\DatabaseInterface;

class MysqlConnection implements DatabaseInterface
{
    private const HOST = "database";
    private const DB_NAME = "wjcrypto";
    private const USER = "root";
    private const PASSWORD = "tiger";
    private const OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ];

    private static $instance;

    private function __constructor()
    {

    }

    /**
     * @return PDO|PDOException
     */
    public static function getInstance(): PDO|PDOException
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
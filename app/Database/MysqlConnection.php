<?php

namespace App\Database;

use PDO;
use PDOException;
use App\Database\DatabaseInterface;

class MysqlConnection implements DatabaseInterface
{
    private const HOST = "localhost";
    private const DB_NAME = "wjcrypto";
    private const USER = "root";
    private const PASSWORD = "triger";
    private const OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ];

    private static $instance;

    private function __constructor()
    {
        $this::$instance = new PDO(
            "mysql:host=" . $this::HOST . ";dbname=" . $this::DB_NAME,
            $this::USER,
            $this::PASSWORD,
            $this::OPTIONS
        );
    }

    public function getInstance(): PDO
    {
        try {
            if (empty($this::$instance)) {
                return $this::$instance;
            }
        } catch (PDOException $exception) {
            var_dump($exception);
        }
    }
}
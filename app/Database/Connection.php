<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection
{
    private static $instance;
    private static $config = [];

    private function __construct()
    {
    }

    /**
     * Get Database configuration from configuration file source
     *
     * @return array
     */
    public static function getDatabaseConfiguration(): array
    {
        $filePath = '/config.json';
        $data = file_get_contents(__DIR__ . $filePath);
        self::$config = json_decode($data, true);

        return self::$config;
    }

    /**
     * Get Instance PDO from Database type
     *
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (!self::$instance) {
            try {
                self::$config = self::getDatabaseConfiguration();
                $dbType = self::$config['db'];

                switch ($dbType) {
                    case 'mysql':
                        self::$instance = new PDO(
                            self::$config['db'] . ":host=" . self::$config['host'] . ";dbname=" . self::$config['dbname'],
                            self::$config['username'],
                            self::$config['password'],
                            self::$config['options']
                        );
                        break;
                }

                return self::$instance;
            } catch (PDOException $e) {
                throw new $e->getMessage();
            }
        }

        return self::$instance;
    }
}

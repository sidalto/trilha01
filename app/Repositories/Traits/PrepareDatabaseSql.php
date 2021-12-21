<?php

namespace App\Repositories\Traits;

use PDO;
use PDOStatement;
use PDOException;

trait PrepareDatabaseSql
{
    protected static PDO $connection;

    /**
     * @param string $query
     * @param array $params
     * @return PDOStatement
     */
    public function prepareBind(string $query, array $params = []): PDOStatement
    {
        try {
            $stmt = self::$connection->prepare($query);

            foreach ($params as $column => $value) {
                $stmt->bindValue($column, $value);
            }

            return $stmt;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * @return string
     */
    public function getInsertId(): string
    {
        try {
            return self::$connection->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
}

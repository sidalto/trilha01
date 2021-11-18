<?php

namespace App\Repositories\Traits;

use RuntimeException;
use PDO;
use PDOException;
use PDOStatement;

trait PrepareDatabaseSql
{
  protected static PDO $connection;

  public function prepareBind(string $query, array $params = []): PDOStatement
  {
    $stmt = self::$connection->prepare($query);

    foreach ($params as $column => $value) {
      $stmt->bindValue($column, $value);
    }

    return $stmt;
  }

  public function getInsertId(): string
  {
    try {
      return self::$connection->lastInsertId();
    } catch (PDOException $e) {
      throw new RunTimeException($e->getMessage());
    }
  }
}

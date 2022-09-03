<?php

declare(strict_types=1);

namespace BlogPortal\Api\Database;

use BlogPortal\Api\{Configuration, Logger};

class Sql {
  private $configuration;
  private $logger;

  /** @var \PDO */
  private $connection;

  /** @var bool */
  private static $inDebug;

  public function __construct(
    Configuration $configuration,
    Logger $logger
  ) {
    $this->configuration = $configuration;
    $this->logger = $logger;
  }

  /**
   * Prepare connection towards database
   *
   * @return void
   */
  public function connect(): void {
    $host = $this->configuration->get('db.host');
    $database = $this->configuration->get('db.database');
    $user = $this->configuration->get('db.user');
    $password = $this->configuration->get('db.password');

    if(self::$inDebug)
      $this->logger->debug(":: Establish connection towards database");

    $this->connection = new \PDO("mysql:host=$host;dbname=$database", $user, $password);
  }

  /**
   * Commits queries that has been stored in transaction
   *
   * @return void
   */
  public function commit(): void {
    if(self::$inDebug)
      $this->logger->debug(":: Commits database transaction");

    $this->connection->commit();
    $this->connection = null;
  }

  /**
   * Rollback query executions that has been made in transaction
   *
   * @return void
   */
  public function rollback(): void {
    if(self::$inDebug)
      $this->logger->debug(":: Rollback database transaction");

    $this->connection->rollback();
    $this->connection = null;
  }

  /**
   * Prepare query to be executed
   *
   * @param string $query
   * @return void
   */
  public function prepare(string $query): \PDOStatement {
    if(empty(trim($query)))
      throw new \Exception('Empty sql query is not allowed');

    if(self::$inDebug)
        $this->logger->debug(":: Prepare SQL query\n$query");

    return $this->connection->prepare($query);
  }

  /**
   * This is to fetch data from a SELECT query PDO statement
   *
   * @param \PDOStatement $stmt
   * @return array
   */
  public function fetch(\PDOStatement $stmt): array {
    try {
      return $stmt->fetch();
    } finally {
      if(self::$inDebug)
        $this->logger->debug(":: Closing connection");

      $stmt = null;
    }
  }

  /**
   * Executes INSERTS, UPDATES or DELETES,
   * INSERTS returnes id of the inserted row.
   *
   * @param \PDOStatement $stmt
   * @return integer|null
   */
  public function execute(\PDOStatement $stmt): ?int {
    try {
      $isInsert = false;
      if(str_starts_with($stmt->queryString, 'insert into'))
        $isInsert = true;

      if(self::$inDebug)
        $this->logger->debug(":: Execute SQL query\n{$stmt->queryString}");

      $stmt->execute();
      if($isInsert){
        $lastInsertId = $this->connection->lastInsertId();
        return $lastInsertId === false ? null : intval($lastInsertId);
      }

      return null;
    } finally {
      if(self::$inDebug)
        $this->logger->debug(":: Closing connection");

      $stmt = null;
    }
  }

  /**
   * This enables debug for database by logging communications
   *
   * @return void
   */
  public static function enableDebug() {
    self::$inDebug = true;
  }
}

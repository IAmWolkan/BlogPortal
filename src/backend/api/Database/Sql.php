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
   * This is to fetch all data from a SELECT query PDO statement
   *
   * @param SqlRaw $sqlRaw
   * @return array
   */
  public function fetchAll(SqlRaw $sqlRaw): array {
    try {
      $stmt = $this->_prepare($sqlRaw);
      $stmt->execute($sqlRaw->getParams());
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
   * @param SqlRaw $sqlRaw
   * @return integer|null
   */
  public function execute(SqlRaw $sqlRaw): ?int {
    try {
      $stmt = $this->_prepare($sqlRaw);

      $isInsert = false;
      if(str_starts_with($stmt->queryString, 'insert into'))
        $isInsert = true;

      if(self::$inDebug)
        $this->logger->debug(":: Execute SQL query\n{$stmt->queryString}");

      $stmt->execute($sqlRaw->getParams());
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

  /**
   * Prepare query to be executed
   *
   * @param string $query
   * @return \PDOStatement
   */
  private function _prepare(SqlRaw $sqlRaw): \PDOStatement {
    if($this->connection === null)
        $this->connect();

    if(empty(trim($sqlRaw->getQuery())))
      throw new \Exception('Empty sql query is not allowed');

    if(self::$inDebug)
      $this->logger->debug(":: Prepare SQL query\n{$sqlRaw->getQuery()}");

    return $this->connection->prepare($sqlRaw->getQuery());
  }
}

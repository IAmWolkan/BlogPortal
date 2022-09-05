<?php

declare(strict_types=1);

namespace BlogPortal\Api\Tasks\Database;

use Auryn\Injector;
use BlogPortal\Api\Database\{Sql, SqlRaw};
use BlogPortal\Api\System;
use BlogPortal\Api\Tasks\BaseTask;
use Robo\Result;

class DemoDb extends BaseTask {
  // Filepath to demo file
  private string $demoFilePath = 'Database/Demo.sql';
  
  private $sql;

  public function __construct() {
    $injector = new Injector();
    System::setup($injector);

    $this->sql = $injector->make(Sql::class);
  }

  /**
   * This task adds demo data to db
   *
   * @return Result
   */
  public function run(): Result {
    try {
      if(!file_exists($this->demoFilePath))
        return Result::error($this, 'Demo.sql file is missing');

      $fileContent = file_get_contents($this->demoFilePath);

      $this->printTaskInfo(":: {$this->demoFilePath}");

      $this->sql->connect();
      $sqlRaw = SqlRaw::create($fileContent);
      $this->sql->execute($sqlRaw);
    } catch(\Exception $ex) {
      $this->logger->error($ex->getMessage(), $ex->getTrace());
      return Result::error($this, $ex->getMessage(), $ex->getTrace());
    }

    return Result::success($this);
  }
}

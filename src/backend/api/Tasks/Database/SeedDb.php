<?php

declare(strict_types=1);

namespace BlogPortal\Api\Tasks\Database;

use Auryn\Injector;
use BlogPortal\Api\Database\{Sql, SqlRaw};
use BlogPortal\Api\System;
use BlogPortal\Api\Tasks\BaseTask;
use Robo\Result;

class SeedDb extends BaseTask {
  // Filepath to seed file
  private string $seedFilePath = 'Database/Seed.sql';
  
  private $sql;

  public function __construct() {
    $injector = new Injector();
    System::setup($injector);

    $this->sql = $injector->make(Sql::class);
  }

  /**
   * This task seeds db with required data
   *
   * @return Result
   */
  public function run(): Result {
    try {
      if(!file_exists($this->seedFilePath))
        return Result::error($this, 'Seed.sql file is missing');

      $fileContent = file_get_contents($this->seedFilePath);

      $this->printTaskInfo(":: {$this->seedFilePath}");

      $this->sql->connect();
      $sqlRaw = SqlRaw::create($fileContent);
      $this->sql->execute($sqlRaw);
    } catch(\Exception $ex) {
      return Result::error($this, $ex->getMessage(), $ex->getTrace());
    }

    return Result::success($this);
  }
}

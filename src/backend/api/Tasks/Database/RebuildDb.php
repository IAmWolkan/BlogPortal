<?php

declare(strict_types=1);

namespace BlogPortal\Api\Tasks\Database;

use Auryn\Injector;
use BlogPortal\Api\Database\Sql;
use BlogPortal\Api\System;
use BlogPortal\Api\Tasks\BaseTask;
use Robo\Result;

class RebuildDb extends BaseTask {
  private $migrationsPath = 'Database/Migrations';

  /** @var Sql */
  private $sql;

  public function __construct() {
    $injector = new Injector();
    System::setup($injector);

    $this->sql = $injector->make(Sql::class);
  }

  /**
   * Main function to run task
   *
   * @return Result
   */
  public function run(): Result {
    try {
      $migrationId = null;

      // Runs trough each migration file
      $migrations = $this->getMigrationList();
      foreach($migrations as $migration) {
        $filePath = "{$this->migrationsPath}/$migration";
        $this->printTaskInfo(":: $filePath");

        // Loads query from migration files
        $migrationContent = file_get_contents($filePath);

        // Connects and execute the query to the database
        $this->sql->connect();
        $stmt = $this->sql->prepare($migrationContent);
        $this->sql->execute($stmt);

        $migrationId = $this->extractMigrationIdFromFilePath($filePath);
      }

      /**
       * Update migration id in database so we can keep track of which
       * migration we run last
       */
      if($migrationId !== null)
        $this->updateMigrationId($migrationId);

      // Returns task result
      return Result::success($this);
    } catch (\Exception $ex) {
      return Result::error($this, $ex->getMessage(), $ex->getTrace());
    }
  }

  /**
   * Updates the migration id on the database,
   * this is used to keep track of what migration we have run so we can
   * run newer migrations without rebuilding the entire database.
   *
   * @param integer $migrationId
   * @return void
   */
  private function updateMigrationId(int $migrationId) {
    $this->sql->connect();
    $stmt = $this->sql->prepare('TRUNCATE `migrations`;');
    $this->sql->execute($stmt);

    $stmt = $this->sql->prepare("INSERT INTO `migrations` VALUES ($migrationId);");
    $this->sql->execute($stmt);
  }

  /**
   * Extracting the migration id from the filename,
   * eg. 007-random-migration.sql would be migration 7 by looking at the prefix.
   *
   * @param string $filePath
   * @return integer
   */
  private function extractMigrationIdFromFilePath(string $filePath): int {
    $paths = explode('/', $filePath);
    $filename = end($paths);
    $migrationId = substr($filename, 0, 3);
    
    return intval($migrationId);
  }

  /**
   * Loads the list of migrations from the specified path.
   *
   * @return array
   */
  private function getMigrationList(): array {
    $files = scandir($this->migrationsPath);
 
    // Only include sql files
    $files = array_filter($files, function($filePath) {
      return str_ends_with($filePath, '.sql');
    });

    return $files;
  }
}

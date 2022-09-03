<?php

use BlogPortal\Api\Tasks\Database\{DemoDb, SeedDb, RebuildDb};

require_once('vendor/autoload.php');

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see https://robo.li/
 */
class RoboFile extends \Robo\Tasks {
  public function dbRebuild() {
    $this->task(RebuildDb::class)->run();
    $this->task(SeedDb::class)->run();
    $this->task(DemoDb::class)->run();
  }

  public function testAll() {
    $this->taskExec('vendor/bin/phpunit --bootstrap tests/bootstrap.php tests')->run();
  }

  public function testUnit() {
    $this->taskExec('vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/Unit')->run();
  }

  public function testIntegration() {
    $this->taskExec('vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/Integration')->run();
  }

  public function testCode(string $args = '') {
    $this->taskExec("vendor/bin/psalm $args")->run();
  }
}

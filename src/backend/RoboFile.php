<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see https://robo.li/
 */
class RoboFile extends \Robo\Tasks {
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

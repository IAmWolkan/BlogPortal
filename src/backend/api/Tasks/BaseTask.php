<?php

declare(strict_types=1);

namespace BlogPortal\Api\Tasks;

use Robo\Result;
use Robo\Task\BaseTask as TaskBaseTask;

abstract class BaseTask extends TaskBaseTask {
  /**
   * @return \Robo\Result
   */
  public function run(): Result {
    return Result::success($this);
  }
}

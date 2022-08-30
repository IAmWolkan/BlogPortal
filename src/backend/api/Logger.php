<?php

declare(strict_types=1);

namespace BlogPortal\Api;

use Monolog\Logger as MonologLogger;

/**
 * This class is only to make sure that if we need to override
 * or do some customizing we don't need to change every log line.
 *
 * @codeCoverageIgnore
 */
class Logger extends MonologLogger {}

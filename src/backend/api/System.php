<?php

declare(strict_types=1);

namespace BlogPortal\Api;

use Auryn\Injector;
use Monolog\Handler\ErrorLogHandler;

/**
 * @codeCoverageIgnore
 */
class System {
  public static function setup(Injector $injector): void {
    // Setup and configure log for later use
    $injector->delegate(Logger::class, function() {
      $log = new Logger('api');
      $log->pushHandler(new ErrorLogHandler());
      return $log;
    });

    // We delegate the injector to the original one so we can easily use it in
    // our factories and other classes that needs access to the injector
    $injector->delegate(Injector::class, function()use($injector) {
      return $injector;
    });

    // Setup injector to share the logger between classes
    $injector->share(Logger::class);
    $injector->share(Injector::class);
  }
}

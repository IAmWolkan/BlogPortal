<?php

declare(strict_types=1);

namespace BlogPortal\Api\Factories;

use Auryn\Injector;
use BlogPortal\Api\Controllers\Controller;
use BlogPortal\Api\Exceptions\MissingControllerException;

class ControllerFactory {
  private $injector;

  public function __construct(Injector $injector) {
    $this->injector = $injector;
  }

  /**
   * Search and return an instance of the requested controller
   *
   * @param string $name
   * @return Controller
   */
  public function create(string $name): Controller {
    $name = strtolower($name);
    $name = ucfirst($name);

    $fullClassPath = "\\BlogPortal\\Api\\Controllers\\{$name}Controller";
    if(class_exists($fullClassPath))
      return $this->injector->make($fullClassPath);
    
    throw new MissingControllerException("$fullClassPath does not exists.");
  }
}

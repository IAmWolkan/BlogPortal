<?php

declare(strict_types=1);

namespace BlogPortal\Api\Factories;

use Auryn\Injector;
use BlogPortal\Api\Exceptions\MissingMatcherException;
use BlogPortal\Api\Network\Matchers\Matcher;

class MatcherFactory {
  private $injector;

  public function __construct(Injector $injector) {
    $this->injector = $injector;  
  }

  /**
   * Search and returns an instance of the requested version matcher
   *
   * @param string $version
   * @return Matcher
   */
  public function create(string $version): Matcher {
    $version = strtoupper($version);
    
    $fullClassPath = "\\BlogPortal\\Api\\Network\\Matchers\\{$version}Matcher";
    if(class_exists($fullClassPath))
      return $this->injector->make($fullClassPath);

    throw new MissingMatcherException("$fullClassPath does not exists.");
  }
}

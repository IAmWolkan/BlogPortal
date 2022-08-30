<?php

declare(strict_types=1);

namespace BlogPortal\Api\Tests\Unit\Factories;

use Auryn\Injector;
use BlogPortal\Api\Exceptions\MissingMatcherException;
use BlogPortal\Api\Factories\MatcherFactory;
use BlogPortal\Api\Network\Matchers\Matcher;
use BlogPortal\Api\Tests\Shared\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class MatcherFactoryTest extends TestCase {
  /**
   * Main class that we are testing
   *
   * @var MatcherFactory
   */
  private $factory;

  /**
   * Mock object of the injector
   *
   * @var MockObject
   */
  private $injector;

  public function setUp(): void {
    $this->injector = $this->createMock(Injector::class);
    $this->factory = new MatcherFactory($this->injector); 
  }

  /**
   * Controls that correct version matcher is sent to injector
   *
   * @return void
   */
  public function testClassIsSentToInjector() {
    $this->injector
      ->expects($this->once())
      ->method('make')
      ->with('\BlogPortal\Api\Network\Matchers\V1Matcher')
      ->willReturn($this->createMock(Matcher::class));

    $this->factory->create('v1');
  }

  /**
   * Makes sure correct exception is thrown when a matcher is missing
   *
   * @return void
   */
  public function testMissingMatcherClassThrowsException() {
    $this->expectException(MissingMatcherException::class);
    $this->factory->create('aRandomMatcherThatShouldNotExists');
  }
}

<?php

declare(strict_types=1);

namespace BlogPortal\Api\Tests\Unit\Factories;

use Auryn\Injector;
use BlogPortal\Api\Controllers\Controller;
use BlogPortal\Api\Exceptions\MissingControllerException;
use BlogPortal\Api\Factories\ControllerFactory;
use BlogPortal\Api\Tests\Shared\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ControllerFactoryTest extends TestCase {
  /**
   * Main class that we are testing
   *
   * @var ControllerFactory
   */
  private $factory;

  /**
   * Mock object of injector
   *
   * @var MockObject
   */
  private $injector;

  public function setUp(): void {
    $this->injector = $this->createMock(Injector::class);
    $this->factory = new ControllerFactory($this->injector);
  }

  /**
   * Controls that correct controller is sent to injector
   *
   * @return void
   */
  public function testClassIsSentToInjector() {
    $this->injector
      ->expects($this->once())
      ->method('make')
      ->with('\BlogPortal\Api\Controllers\PostController')
      ->willReturn($this->createMock(Controller::class));

    $this->factory->create('pOsT');
  }

  /**
   * Makes sure correct exception is thrown when controller is missing
   *
   * @return void
   */
  public function testMissingControllerClassThrowsException() {
    $this->expectException(MissingControllerException::class);
    $this->factory->create('aRandomControllerThatShouldNotExists');
  }
}

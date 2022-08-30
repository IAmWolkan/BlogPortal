<?php

declare(strict_types=1);

namespace BlogPortal\Api\Tests\Unit\Network\Matchers;

use BlogPortal\Api\Controllers\Controller;
use BlogPortal\Api\Factories\ControllerFactory;
use BlogPortal\Api\Network\Matchers\V1Matcher;
use BlogPortal\Api\Services\RequestService;
use BlogPortal\Api\Tests\Shared\TestCase;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\MockObject\MockObject;

class V1MatcherTest extends TestCase {
  /**
   * The matcher we are testing
   *
   * @var V1Matcher
   */
  private $matcher;

  /**
   * Mock of controller factory that is used during tests
   *
   * @var MockObject
   */
  private $controllerFactory;

  /**
   * Mock of request service that is used during tests
   *
   * @var MockObject
   */
  private $requestService;

  public function setUp(): void {
    $this->controllerFactory = $this->createMock(ControllerFactory::class);
    $this->requestService = $this->createMock(RequestService::class);

    $this->matcher = new V1Matcher(
      $this->controllerFactory,
      $this->requestService
    );
  }

  /**
   * Makes sure the correct request is used
   *
   * @return void
   */
  public function testCorrectRequestIsSentToRequestService() {
    $request = Request::create('/v1/post/1');

    $this->requestService
      ->expects($this->once())
      ->method('getControllerNameFromUri')
      ->with($request);

    $this->matcher->findAndCall($request);
  }

  /**
   * Checks that we are using correct name when calling factory
   *
   * @return void
   */
  public function testCorrectControllerNameIsSentToFactory() {
    $request = Request::create('/v1/post/1');

    $controllerName = 'post';
    $this->requestService
      ->method('getControllerNameFromUri')
      ->with($request)
      ->willReturn($controllerName);

    $this->controllerFactory
      ->expects($this->once())
      ->method('create')
      ->with($controllerName);

    $this->matcher->findAndCall($request);
  }

  /**
   * Makes sure that we are calling the correct controller with the correct action method
   *
   * @return void
   */
  public function testCorrectControllerIsCalledWithAction() {
    $request = Request::create('/v1/post/1', 'POST');

    $controllerName = 'category';
    $this->requestService
      ->method('getControllerNameFromUri')
      ->with($request)
      ->willReturn($controllerName);

    /**
     * @var MockObject
     */
    $controller = $this->createMock(Controller::class);
    $controller
      ->expects($this->once())
      ->method('post')
      ->with($request);

    $this->controllerFactory
      ->method('create')
      ->with($controllerName)
      ->willReturn($controller);

    $this->matcher->findAndCall($request);
  }
}

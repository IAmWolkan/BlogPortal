<?php

declare(strict_types=1);

namespace BlogPortal\Api\Tests\Unit\Network;

use BlogPortal\Api\Exceptions\{
  MissingControllerException, MissingMatcherException
};
use BlogPortal\Api\Factories\MatcherFactory;
use BlogPortal\Api\Logger;
use BlogPortal\Api\Network\Matchers\Matcher;
use BlogPortal\Api\Network\Router;
use BlogPortal\Api\Services\RequestService;
use BlogPortal\Api\Tests\Shared\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;

class RouterTest extends TestCase {
  /**
   * Main class that we are testing
   *
   * @var Router
   */
  private $router;

  /**
   * Mock object of the logger
   *
   * @var MockObject
   */
  private $logger;

  /**
   * Mock object of the matcher factory
   *
   * @var MockObject
   */
  private $matcherFactory;

  /**
   * Mock object of the request service
   *
   * @var MockObject
   */
  private $requestService;

  public function setUp(): void {
    $this->logger = $this->createMock(Logger::class);
    $this->matcherFactory = $this->createMock(MatcherFactory::class);
    $this->requestService = $this->createMock(RequestService::class);

    $this->router = new Router(
      $this->logger,
      $this->matcherFactory,
      $this->requestService
    );
  }

  /**
   * We are testing that missing version matchers returns an response with http code 410
   *
   * @return void
   */
  public function testMissingMatcherReturnsHttpCode410() {
    $request = Request::create('');

    $this->matcherFactory
      ->method('create')
      ->willThrowException(new MissingMatcherException('Expected exception'));

    $response = $this->router->execute($request);
    $this->assertEquals(410, $response->getStatusCode());
  }

  /**
   * We are testing that missing controllers returns an response with http code 404
   *
   * @return void
   */
  public function testMissingControllerReturnsHttpCode404() {
    $request = Request::create('');

    /**
     * @var MockObject
     */
    $matcher = $this->createMock(Matcher::class);
    $matcher
      ->method('findAndCall')
      ->willThrowException(new MissingControllerException('Expected exception'));

    $this->matcherFactory
      ->method('create')
      ->willReturn($matcher);

    $response = $this->router->execute($request);
    $this->assertEquals(404, $response->getStatusCode());
  }

  /**
   * We are testing that unexpected error returns an response with http code 500
   *
   * @return void
   */
  public function testUnexpectedErrorReturnsHttpCode500() {
    $request = Request::create('');

    /**
     * @var MockObject
     */
    $matcher = $this->createMock(Matcher::class);
    $matcher
      ->method('findAndCall')
      ->willThrowException(new \Exception('Expected exception'));

    $this->matcherFactory
      ->method('create')
      ->willReturn($matcher);

    $response = $this->router->execute($request);
    $this->assertEquals(500, $response->getStatusCode());
  }
}

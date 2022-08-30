<?php

declare(strict_types=1);

namespace BlogPortal\Api\Tests\Unit\Services;

use BlogPortal\Api\Services\RequestService;
use BlogPortal\Api\Tests\Shared\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RequestServiceTest extends TestCase {
  /**
   * Main class that is being tested
   *
   * @var RequestService
   */
  private $service;

  /**
   * Main class that set's up the class we are testing
   *
   * @return void
   */
  public function setUp(): void {
    $this->service = new RequestService();
  }

  /**
   * Here we are testing that we are retrieving the correct controller name
   * from the uri.
   *
   * @return void
   */
  public function testGetControllerNameFromUri() {
    $request = Request::create('/v1/post/1');

    $controllerName = $this->service->getControllerNameFromUri($request);
    $this->assertEquals('post', $controllerName);
  }

  /**
   * Here we are testing that we are retrieving the correct api version
   * from the uri.
   *
   * @return void
   */
  public function testGetApiVersionFromUri() {
    $request = Request::create('/v1/post/1');

    $controllerName = $this->service->getApiVersionFromUri($request);
    $this->assertEquals('v1', $controllerName);
  }
}

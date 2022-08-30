<?php

declare(strict_types=1);

namespace BlogPortal\Api\Network\Matchers;

use BlogPortal\Api\Factories\ControllerFactory;
use BlogPortal\Api\Services\RequestService;
use Symfony\Component\HttpFoundation\{Request, Response};

class V1Matcher implements Matcher {
  private $controllerFactory;
  private $requestService;

  public function __construct(
    ControllerFactory $controllerFactory,
    RequestService $requestService
  ) {
    $this->controllerFactory = $controllerFactory;
    $this->requestService = $requestService;
  }

  public function findAndCall(Request $request): Response {
    $action = $request->getMethod();
    $action = strtolower($action);

    $controllerName = $this->requestService->getControllerNameFromUri($request);
    $controller = $this->controllerFactory->create($controllerName);

    return $controller->$action($request);
  }
}

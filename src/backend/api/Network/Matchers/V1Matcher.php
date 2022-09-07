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

  /**
   * Finds and executes the correct controller based on uri
   *
   * @param Request $request
   * @return Response
   */
  public function findAndCall(Request $request): Response {
    $action = $request->getMethod();
    $action = strtolower($action);

    // Extracts the controller name from the uri and creates the controller
    $controllerName = $this->requestService->getControllerNameFromUri($request);
    $controller = $this->controllerFactory->create($controllerName);

    // Extracts the entity id from the uri and adds it to the request 
    $entityId = $this->requestService->getEntityIdFromUri($request);
    if($entityId !== null)
      $request->attributes->set('id', $entityId);

    // Runs the action on the controller with the request object
    return $controller->$action($request);
  }
}

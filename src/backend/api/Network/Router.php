<?php

declare(strict_types=1);

namespace BlogPortal\Api\Network;

use BlogPortal\Api\Exceptions\{
  MissingControllerException, MissingMatcherException
};
use BlogPortal\Api\Factories\MatcherFactory;
use BlogPortal\Api\Logger;
use BlogPortal\Api\Services\RequestService;
use Symfony\Component\HttpFoundation\{Request, Response};

class Router {
  private $logger;
  private $matcherFactory;
  private $requestService;

  public function __construct(
    Logger $logger,
    MatcherFactory $matcherFactory,
    RequestService $requestService
  ) {
    $this->logger = $logger;
    $this->matcherFactory = $matcherFactory;
    $this->requestService = $requestService;
  }

  public function execute(Request $request): Response {
    try {
      $version = $this->requestService->getApiVersionFromUri($request);
      $matcher = $this->matcherFactory->create($version);
      
      return $matcher->findAndCall($request);
    } catch(MissingMatcherException $ex) {
      $this->logger->warning($ex->getMessage());
      return new Response('', 410);
    } catch(MissingControllerException $ex) {
      $this->logger->warning($ex->getMessage());
      return new Response('', 404);
    } catch(\Exception $ex) {
      $this->logger->error($ex->getMessage(), $ex->getTrace());
      return new Response('', 500);
    }
  }
}

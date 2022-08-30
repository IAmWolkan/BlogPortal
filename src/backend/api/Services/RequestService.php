<?php

declare(strict_types=1);

namespace BlogPortal\Api\Services;

use Symfony\Component\HttpFoundation\Request;

class RequestService {
  /**
   * Extracts the controller name from the URI
   *
   * @param Request $request
   * @return string
   */
  public function getControllerNameFromUri(Request $request): string {
    $paths = $this->splitApiUrl($request);
    $controllerName = $paths[1];

    return $controllerName;
  }

  /**
   * Extracts the api version from the URI 
   *
   * @param Request $request
   * @return string
   */
  public function getApiVersionFromUri(Request $request): string {
    $paths = $this->splitApiUrl($request);
    $version = $paths[0];

    return $version;
  }

  /**
   * Splits up the URI to multiple parts
   *
   * @param Request $request
   * @return array
   */
  private function splitApiUrl(Request $request): array {
    $fullPath = $request->getPathInfo();
    $paths = explode('/', $fullPath);
    $paths = array_filter($paths);
    return array_values($paths);
  }
}

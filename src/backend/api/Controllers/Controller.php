<?php

declare(strict_types=1);

namespace BlogPortal\Api\Controllers;

use Symfony\Component\HttpFoundation\{Request, Response};

interface Controller {
  public function get(Request $request): Response;

  public function post(Request $request): Response;

  public function put(Request $request): Response;

  public function delete(Request $request): Response;
}

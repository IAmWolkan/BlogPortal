<?php

declare(strict_types=1);

namespace BlogPortal\Api\Network\Matchers;

use Symfony\Component\HttpFoundation\{Request, Response};

interface Matcher {
  public function findAndCall(Request $request): Response;
}

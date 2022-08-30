<?php

declare(strict_types=1);

namespace BlogPortal\Api;

require_once("vendor/autoload.php");

use Auryn\Injector;
use BlogPortal\Api\Network\Router;
use BlogPortal\Api\System;
use Symfony\Component\HttpFoundation\Request;

// Setup injector before we do anything else
$injector = new Injector();
System::setup($injector);

// Create request object from request call
$request = Request::createFromGlobals();

// Setup and call router to find and send data to correct controller 
$router = $injector->make(Router::class);
$response = $router->execute($request);

// Sends the response to the user
$response->send();

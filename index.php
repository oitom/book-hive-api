<?php
header('Content-Type: application/json');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Presentation/RouteHandler.php';

use App\Presentation\RouteHandler;

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$routeHandler = new RouteHandler();
$response = $routeHandler->handle($requestUri, $requestMethod);

echo $response;
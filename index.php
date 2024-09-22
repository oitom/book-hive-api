<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Presentation/Routers/RouteHandler.php';

use App\Presentation\Routers\RouteHandler;

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$routeHandler = new RouteHandler();
$response = $routeHandler->handle($requestUri, $requestMethod);

echo $response;
<?php

namespace App\Presentation\Router;

class RouteHandler
{
  private array $routes;
  public function __construct()
  {
    $this->routes = require __DIR__ . '/routes.php';
  }

  public function handle(string $uri, string $method)
  {
    if (isset($this->routes[$method][$uri])) {
      [$controllerClass, $action] = explode('@', $this->routes[$method][$uri]);

      $controller = new $controllerClass();
      return $controller->$action();
    }

    return $this->notFoundResponse();
  }

  private function notFoundResponse()
  {
    http_response_code(404);
    return json_encode(['message' => 'Route not found']);
  }
}

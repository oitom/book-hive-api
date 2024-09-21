<?php

namespace App\Presentation\Router;

class RouteHandler
{
  private array $routes;
  private array $headers;
  private array $body;
  private array $queryParams;
  public function __construct()
  {
    $this->routes = require __DIR__ . '/routes.php';
    $this->headers = getallheaders();
    $this->body = json_decode(file_get_contents('php://input'), true);
    $this->queryParams = $_GET;
  }

  public function handle(string $uri, string $method)
  {
    if (isset($this->routes[$method][$uri])) {
      [$controllerClass, $action] = explode('@', $this->routes[$method][$uri]);

      $controller = new $controllerClass($this->headers, $this->body, $this->queryParams);
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

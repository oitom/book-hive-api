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
    $this->body = json_decode(file_get_contents('php://input'), true) ?? [];
    $this->queryParams = $_GET;
  }

  public function handle(string $uri, string $method)
  {
    foreach ($this->routes[$method] as $route => $action) {
      // Cria uma expressão regular para capturar parâmetros
      $routePattern = preg_replace('/:\w+/', '(\d+)', $route); 
      if (preg_match("#^$routePattern$#", $uri, $matches)) {
        [$controllerClass, $actionMethod] = explode('@', $action);
        $controller = new $controllerClass($this->headers, $this->body, $this->queryParams);

        // Remove o índice 0, que contém a string completa
        array_shift($matches);
        
        // enviar o paramentro (:id) para a action da controller
        return $controller->$actionMethod(...$matches);
      }
    }

    return $this->notFoundResponse();
  }

  private function notFoundResponse()
  {
    http_response_code(404);
    return json_encode(['message' => 'Route not found']);
  }
}

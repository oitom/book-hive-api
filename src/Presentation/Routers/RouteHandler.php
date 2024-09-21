<?php

namespace App\Presentation\Routers;

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
    // Remove a query string (tudo após o "?") da URI para a comparação
    $uri = parse_url($uri, PHP_URL_PATH);

    foreach ($this->routes[$method] as $route => $action) {
      $routePattern = preg_replace('/:\w+/', '(\d+)', $route);
      $routePattern = str_replace('/', '\/', $routePattern);

      // Match para rotas exatas
      if (preg_match("#^$routePattern$#", $uri, $matches)) {
        [$controllerClass, $actionMethod] = explode('@', $action);
        $controller = new $controllerClass($this->headers, $this->body, $this->queryParams);

        array_shift($matches); // Remove o índice 0, com a string completa

        // Executa a action do controller
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

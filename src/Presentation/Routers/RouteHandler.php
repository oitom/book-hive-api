<?php

namespace App\Presentation\Routers;

use App\Presentation\Routers\DefaultHeaderProvider;
use App\Presentation\Routers\HeaderProviderInterface;

class RouteHandler
{
  private array $routes;
  private array $headers;
  private array $body;
  private array $queryParams;

  public function __construct(HeaderProviderInterface $headerProvider = null)
  {
    $this->routes = require __DIR__ . '/routes.php';
    
    if ($headerProvider === null) {
      $headerProvider = new DefaultHeaderProvider();
    }

    $this->headers = $headerProvider->getHeaders();
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

<?php

namespace App\Presentation\Controller;

class BaseController
{
  protected $headers;
  protected $body;
  protected $queryParams;

  public function __construct($headers, $body, $queryParams)
  {
    $this->headers = $headers;
    $this->body = $body;
    $this->queryParams = $queryParams;
  }

  protected function getHeader(string $name)
  {
    return $this->headers[$name] ?? null;
  }

  protected function getBody()
  {
    return $this->body;
  }

  protected function getQueryParam(string $name)
  {
    return $this->queryParams[$name] ?? null;
  }

  protected function sendErrorResponse(array $errors, int $statusCode = 400)
  {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode(['errors' => $errors]);
    exit;
  }

  protected function sendSuccessResponse(array $data = [], string $message = '', int $statusCode = 200)
  {
    http_response_code($statusCode);
    header('Content-Type: application/json');

    $response = ['message' => $message];
    if (!empty($data)) {
        $response['data'] = $data;
    }

    echo json_encode($response);
    exit;
  }
}

<?php

namespace App\Presentation\Controllers;
use App\Presentation\Enums\HttpCodesEnum;

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

  public function sendErrorResponse(array $errors, int $statusCode = HttpCodesEnum::HTTP_BAD_REQUEST)
  {
    $this->setHttpResponseCode($statusCode);
    $this->sendHeader('Content-Type: application/json');
    echo json_encode(['errors' => $errors]);
    $this->terminate();
  }

  public function sendSuccessResponse(array $data = [], string $message = 'success', int $statusCode = HttpCodesEnum::HTTP_OK)
  {
    $this->setHttpResponseCode($statusCode);
    $this->sendHeader('Content-Type: application/json');

    $response = ['message' => $message];
    if (!empty($data)) {
      $response['data'] = $data;
    }

    echo json_encode($response);
    $this->terminate();
  }

  protected function setHttpResponseCode(int $code)
  {
    http_response_code($code);
  }

  protected function sendHeader(string $header)
  {
    header($header);
  }

  // Método para simular o exit()
  protected function terminate()
  {
    exit;
  }
}

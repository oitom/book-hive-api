<?php

use PHPUnit\Framework\TestCase;
use App\Presentation\Controllers\BaseController;
use App\Presentation\Enums\HttpCodesEnum;

class BaseControllerTest extends TestCase
{
  private $headers;
  private $body;
  private $queryParams;
  private $controller;

  protected function setUp() : void
  {
    $this->headers = [
      'Authorization' => 'Bearer test-token',
      'Content-Type'  => 'application/json',
    ];

    $this->body = [
      'key' => 'value',
    ];

    $this->queryParams = [
      'page' => 1,
    ];

    $this->controller = $this->getMockBuilder(BaseController::class)
      ->setConstructorArgs([$this->headers, $this->body, $this->queryParams])
      ->onlyMethods(['terminate', 'setHttpResponseCode', 'sendHeader'])
      ->getMock();
  }

  private function invokeProtectedMethod($object, $methodName, array $parameters = [])
  {
    $reflection = new \ReflectionClass($object);
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(true);

    return $method->invokeArgs($object, $parameters);
  }

  public function testSetHttpResponseCode()
  {
    $this->controller->expects($this->once())
      ->method('setHttpResponseCode')
      ->with(HttpCodesEnum::HTTP_BAD_REQUEST);

    $this->controller->sendErrorResponse(['Invalid data']);
  }

  public function testSendHeader()
  {
    $this->controller->expects($this->once())
      ->method('sendHeader')
      ->with('Content-Type: application/json');

    $this->controller->sendErrorResponse(['Invalid data']);
  }

  public function testTerminate()
  {
    $this->controller->expects($this->once())
      ->method('terminate');

    $this->controller->sendErrorResponse(['Invalid data']);
  }

  public function testSendSuccessResponse()
  {
    $this->controller->expects($this->once())
      ->method('setHttpResponseCode')
      ->with(HttpCodesEnum::HTTP_OK);

    $this->controller->expects($this->once())
      ->method('sendHeader')
      ->with('Content-Type: application/json');

    $this->controller->expects($this->once())
      ->method('terminate');

    $this->expectOutputString('{"message":"success"}');

    $this->controller->sendSuccessResponse();
  }

  public function testSendSuccessResponseWithData()
  {
    $this->controller->expects($this->once())
      ->method('setHttpResponseCode')
      ->with(HttpCodesEnum::HTTP_OK);

    $this->controller->expects($this->once())
      ->method('sendHeader')
      ->with('Content-Type: application/json');

    $this->controller->expects($this->once())
      ->method('terminate');

    $this->expectOutputString('{"message":"success","data":{"key":"value"}}');

    $this->controller->sendSuccessResponse(['key' => 'value']);
  }

  public function testSendSuccessResponseWithCustomMessage()
  {
    $this->controller->expects($this->once())
      ->method('setHttpResponseCode')
      ->with(HttpCodesEnum::HTTP_OK);

    $this->controller->expects($this->once())
      ->method('sendHeader')
      ->with('Content-Type: application/json');

    $this->controller->expects($this->once())
      ->method('terminate');

    $this->expectOutputString('{"message":"custom message"}');

    $this->controller->sendSuccessResponse([], 'custom message');
  }
}

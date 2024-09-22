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

    protected function setUp(): void
    {
        // Simulando valores para headers, body e query params
        $this->headers = [
            'Authorization' => 'Bearer test-token',
            'Content-Type' => 'application/json'
        ];

        $this->body = [
            'key' => 'value'
        ];

        $this->queryParams = [
            'page' => 1
        ];

        // Criando instância do BaseController mockando os métodos
        $this->controller = $this->getMockBuilder(BaseController::class)
                                 ->setConstructorArgs([$this->headers, $this->body, $this->queryParams])
                                 ->onlyMethods(['terminate', 'setHttpResponseCode', 'sendHeader'])
                                 ->getMock();
    }

    // Método auxiliar para acessar métodos protegidos usando reflexão
    private function invokeProtectedMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testSetHttpResponseCode()
    {
        // Mock do setHttpResponseCode para evitar que a função real seja chamada
        $this->controller->expects($this->once())
                         ->method('setHttpResponseCode')
                         ->with(HttpCodesEnum::HTTP_BAD_REQUEST);

        // Chamando o método sendErrorResponse que deve invocar o setHttpResponseCode
        $this->controller->sendErrorResponse(['Invalid data']);
    }

    public function testSendHeader()
    {
        // Mock do sendHeader para evitar que a função real seja chamada
        $this->controller->expects($this->once())
                         ->method('sendHeader')
                         ->with('Content-Type: application/json');

        // Chamando o método sendErrorResponse que deve invocar o sendHeader
        $this->controller->sendErrorResponse(['Invalid data']);
    }

    public function testTerminate()
    {
        // Sobrescrevendo a chamada de terminate para evitar o exit
        $this->controller->expects($this->once())
                         ->method('terminate');

        // Chamando o método sendErrorResponse para verificar se terminate é chamado
        $this->controller->sendErrorResponse(['Invalid data']);
    }

    public function testSendSuccessResponse()
    {
        // Mock do setHttpResponseCode e sendHeader
        $this->controller->expects($this->once())
                         ->method('setHttpResponseCode')
                         ->with(HttpCodesEnum::HTTP_OK);

        $this->controller->expects($this->once())
                         ->method('sendHeader')
                         ->with('Content-Type: application/json');

        // Mock do terminate para evitar o exit
        $this->controller->expects($this->once())
                         ->method('terminate');

        // Captura a saída gerada pelo echo
        $this->expectOutputString('{"message":"success"}');

        // Chamando o método sendSuccessResponse
        $this->controller->sendSuccessResponse();
    }

    public function testSendSuccessResponseWithData()
    {
        // Mock do setHttpResponseCode e sendHeader
        $this->controller->expects($this->once())
                         ->method('setHttpResponseCode')
                         ->with(HttpCodesEnum::HTTP_OK);

        $this->controller->expects($this->once())
                         ->method('sendHeader')
                         ->with('Content-Type: application/json');

        // Mock do terminate para evitar o exit
        $this->controller->expects($this->once())
                         ->method('terminate');

        // Captura a saída gerada pelo echo com dados
        $this->expectOutputString('{"message":"success","data":{"key":"value"}}');

        // Chamando o método sendSuccessResponse com dados
        $this->controller->sendSuccessResponse(['key' => 'value']);
    }

    public function testSendSuccessResponseWithCustomMessage()
    {
        // Mock do setHttpResponseCode e sendHeader
        $this->controller->expects($this->once())
                         ->method('setHttpResponseCode')
                         ->with(HttpCodesEnum::HTTP_OK);

        $this->controller->expects($this->once())
                         ->method('sendHeader')
                         ->with('Content-Type: application/json');

        // Mock do terminate para evitar o exit
        $this->controller->expects($this->once())
                         ->method('terminate');

        // Captura a saída gerada pelo echo com mensagem personalizada
        $this->expectOutputString('{"message":"custom message"}');

        // Chamando o método sendSuccessResponse com mensagem personalizada
        $this->controller->sendSuccessResponse([], 'custom message');
    }
}

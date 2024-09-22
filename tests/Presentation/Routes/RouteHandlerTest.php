<?php

namespace Tests\Presentation\Routes;

use App\Presentation\Routers\RouteHandler;
use PHPUnit\Framework\TestCase;

class TestController
{
    public function testMethod()
    {
        return json_encode(['success' => true]);
    }
}

class RouteHandlerTest extends TestCase
{
    protected RouteHandler $routeHandler;

    protected function setUp(): void
    {
        // Mocking headers, body, and query params
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = ['param1' => 'value1'];
        file_put_contents('php://input', json_encode(['key' => 'value']));


        $this->routeHandler = new RouteHandler();
    }

    public function testHandleValidRoute()
    {
        // Mock routes for testing
        $routes = [
            'GET' => [
                '/test' => 'Tests\Presentation\Routes\TestController@testMethod'
            ]
        ];

        // Simulate the routes.php file
        $this->setPrivateProperty($this->routeHandler, 'routes', $routes);

        $response = $this->routeHandler->handle('/test', 'GET');

        $this->assertEquals(json_encode(['success' => true]), $response);
    }

    public function testHandleNotFoundRoute()
    {
        // Mock routes for testing
        $routes = [
            'GET' => []
        ];

        // Simulate the routes.php file
        $this->setPrivateProperty($this->routeHandler, 'routes', $routes);

        $response = $this->routeHandler->handle('/non-existent-route', 'GET');

        $this->assertEquals(json_encode(['message' => 'Route not found']), $response);
    }

    protected function setPrivateProperty($object, $propertyName, $value)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}

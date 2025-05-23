<?php

declare(strict_types=1);

namespace Tests\Application\Actions\CV;

use Tests\TestCase;

class CVActionTest extends TestCase
{
    public function testCVActionReturnsSuccessfulResponse(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/cv');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCVActionRendersTemplate(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/cv');
        $response = $app->handle($request);

        $body = (string) $response->getBody();
        
        $this->assertNotEmpty($body);
        $this->assertIsString($body);
    }

    public function testCVActionUsesGetMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/cv');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCVActionRejectsPostMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('POST', '/cv');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testCVActionRejectsPutMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('PUT', '/cv');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testCVActionRejectsDeleteMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('DELETE', '/cv');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testCVActionWithHtmlAcceptHeader(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest(
            'GET', 
            '/cv', 
            ['HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8']
        );
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCVActionWithJsonAcceptHeader(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest(
            'GET', 
            '/cv', 
            ['HTTP_ACCEPT' => 'application/json']
        );
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
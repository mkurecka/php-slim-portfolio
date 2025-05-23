<?php

declare(strict_types=1);

namespace Tests\Application\Actions;

use App\Application\Actions\HomeAction;
use Tests\TestCase;

class HomeActionTest extends TestCase
{
    public function testHomeActionReturnsSuccessfulResponse(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testHomeActionRendersTemplate(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/');
        $response = $app->handle($request);

        $body = (string) $response->getBody();
        
        $this->assertNotEmpty($body);
        $this->assertIsString($body);
    }

    public function testHomeActionUsesGetMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testHomeActionRejectsPostMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('POST', '/');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testHomeActionRejectsPutMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('PUT', '/');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testHomeActionRejectsDeleteMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('DELETE', '/');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }
}
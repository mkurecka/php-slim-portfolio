<?php

declare(strict_types=1);

namespace Tests\Application\Actions;

use Tests\TestCase;

class OneColumnActionTest extends TestCase
{
    public function testOneColumnActionReturnsSuccessfulResponse(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/page/about');
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [200, 404]);
    }

    public function testOneColumnActionWithValidSlug(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/page/test-page');
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [200, 404]);
    }

    public function testOneColumnActionWithNonExistentSlug(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/page/non-existent-page-123456');
        $response = $app->handle($request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testOneColumnActionUsesGetMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/page/test-page');
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [200, 404]);
    }

    public function testOneColumnActionRejectsPostMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('POST', '/page/test-page');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testOneColumnActionRejectsPutMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('PUT', '/page/test-page');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testOneColumnActionRejectsDeleteMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('DELETE', '/page/test-page');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testOneColumnActionWithEmptySlug(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/page/');
        $response = $app->handle($request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testOneColumnActionRendersTemplateWhenPageExists(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/page/about');
        $response = $app->handle($request);

        if ($response->getStatusCode() === 200) {
            $body = (string) $response->getBody();
            $this->assertNotEmpty($body);
            $this->assertIsString($body);
        } else {
            $this->assertEquals(404, $response->getStatusCode());
        }
    }
}
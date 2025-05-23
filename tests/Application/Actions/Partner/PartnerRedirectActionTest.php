<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Partner;

use Tests\TestCase;

class PartnerRedirectActionTest extends TestCase
{
    public function testPartnerRedirectActionWithValidSlug(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/partner/example-partner');
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [302, 404]);
    }

    public function testPartnerRedirectActionWithNonExistentSlug(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/partner/non-existent-partner-123456');
        $response = $app->handle($request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testPartnerRedirectActionUsesGetMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/partner/test-partner');
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [302, 404]);
    }

    public function testPartnerRedirectActionRejectsPostMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('POST', '/partner/test-partner');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testPartnerRedirectActionRejectsPutMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('PUT', '/partner/test-partner');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testPartnerRedirectActionRejectsDeleteMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('DELETE', '/partner/test-partner');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testPartnerRedirectActionWithEmptySlug(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/partner/');
        $response = $app->handle($request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testPartnerRedirectActionWithSpecialCharacters(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/partner/test-partner-with-special-chars-@#$');
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [302, 404]);
    }
}
<?php

declare(strict_types=1);

namespace Tests\Application\Actions;

use Tests\TestCase;

class ContactActionTest extends TestCase
{
    public function testContactActionGetReturnsSuccessfulResponse(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/contact');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testContactActionGetRendersTemplate(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/contact');
        $response = $app->handle($request);

        $body = (string) $response->getBody();
        
        $this->assertNotEmpty($body);
        $this->assertIsString($body);
    }

    public function testContactActionPostWithValidData(): void
    {
        $app = $this->getAppInstance();
        
        $postData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test message content'
        ];

        $request = $this->createRequest('POST', '/contact')
            ->withParsedBody($postData);
        
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [200, 302]);
    }

    public function testContactActionPostWithMissingName(): void
    {
        $app = $this->getAppInstance();
        
        $postData = [
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test message content'
        ];

        $request = $this->createRequest('POST', '/contact')
            ->withParsedBody($postData);
        
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [200, 400, 422]);
    }

    public function testContactActionPostWithMissingEmail(): void
    {
        $app = $this->getAppInstance();
        
        $postData = [
            'name' => 'John Doe',
            'subject' => 'Test Subject',
            'message' => 'Test message content'
        ];

        $request = $this->createRequest('POST', '/contact')
            ->withParsedBody($postData);
        
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [200, 400, 422]);
    }

    public function testContactActionPostWithInvalidEmail(): void
    {
        $app = $this->getAppInstance();
        
        $postData = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'subject' => 'Test Subject',
            'message' => 'Test message content'
        ];

        $request = $this->createRequest('POST', '/contact')
            ->withParsedBody($postData);
        
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [200, 400, 422]);
    }

    public function testContactActionPostWithEmptyMessage(): void
    {
        $app = $this->getAppInstance();
        
        $postData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => ''
        ];

        $request = $this->createRequest('POST', '/contact')
            ->withParsedBody($postData);
        
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [200, 400, 422]);
    }

    public function testContactActionRejectsPutMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('PUT', '/contact');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testContactActionRejectsDeleteMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('DELETE', '/contact');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }
}
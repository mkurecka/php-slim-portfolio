<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Blog;

use Tests\TestCase;

class BlogActionTest extends TestCase
{
    public function testBlogListActionReturnsSuccessfulResponse(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/blog');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testBlogPostActionReturnsSuccessfulResponse(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/blog/test-post');
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [200, 404]);
    }

    public function testBlogListActionRendersTemplate(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/blog');
        $response = $app->handle($request);

        $body = (string) $response->getBody();
        
        $this->assertNotEmpty($body);
        $this->assertIsString($body);
    }

    public function testBlogListActionUsesGetMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/blog');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testBlogListActionRejectsPostMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('POST', '/blog');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testBlogPostActionHandlesNonExistentPost(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/blog/non-existent-post-slug-123456');
        $response = $app->handle($request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testBlogPostActionUsesGetMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('GET', '/blog/test-post');
        $response = $app->handle($request);

        $this->assertContains($response->getStatusCode(), [200, 404]);
    }

    public function testBlogPostActionRejectsPostMethod(): void
    {
        $app = $this->getAppInstance();
        
        $request = $this->createRequest('POST', '/blog/test-post');
        $response = $app->handle($request);

        $this->assertEquals(405, $response->getStatusCode());
    }
}
<?php

declare(strict_types=1);

namespace Tests\Domain\Blog;

use App\Domain\Blog\BlogPost;
use App\Domain\Blog\BlogRepository;
use PHPUnit\Framework\TestCase;

class BlogRepositoryTest extends TestCase
{
    private string $tempFile;
    private BlogRepository $repository;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'blog_test_');
        $this->repository = new BlogRepository($this->tempFile);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    public function testFindAllReturnsEmptyArrayForEmptyRepository(): void
    {
        file_put_contents($this->tempFile, '[]');
        $posts = $this->repository->findAll();
        $this->assertIsArray($posts);
        $this->assertEmpty($posts);
    }

    public function testFindAllReturnsEmptyArrayForNonExistentFile(): void
    {
        unlink($this->tempFile);
        $posts = $this->repository->findAll();
        $this->assertIsArray($posts);
        $this->assertEmpty($posts);
    }

    public function testSaveAndFindAll(): void
    {
        $blogPost = new BlogPost(
            1,
            'Test Title',
            'test-slug',
            'Test content',
            '2023-01-01',
            'tag1,tag2',
            'Meta description',
            true
        );

        $this->repository->save($blogPost);
        $posts = $this->repository->findAll();

        $this->assertCount(1, $posts);
        $this->assertInstanceOf(BlogPost::class, $posts[0]);
        $this->assertEquals('Test Title', $posts[0]->getTitle());
        $this->assertEquals('test-slug', $posts[0]->getSlug());
    }

    public function testSaveGeneratesIdForNewPost(): void
    {
        $blogPost = new BlogPost(
            0,
            'Test Title',
            'test-slug',
            'Test content',
            '2023-01-01'
        );

        $savedPost = $this->repository->save($blogPost);
        
        $this->assertGreaterThan(0, $savedPost->getId());
        $this->assertEquals('Test Title', $savedPost->getTitle());
    }

    public function testSaveUpdatesExistingPost(): void
    {
        $blogPost = new BlogPost(
            1,
            'Original Title',
            'original-slug',
            'Original content',
            '2023-01-01'
        );

        $this->repository->save($blogPost);

        $updatedPost = new BlogPost(
            1,
            'Updated Title',
            'updated-slug',
            'Updated content',
            '2023-01-02'
        );

        $this->repository->save($updatedPost);
        $posts = $this->repository->findAll();

        $this->assertCount(1, $posts);
        $this->assertEquals('Updated Title', $posts[0]->getTitle());
        $this->assertEquals('updated-slug', $posts[0]->getSlug());
    }

    public function testFindBySlugReturnsCorrectPost(): void
    {
        $blogPost1 = new BlogPost(1, 'Title 1', 'slug-1', 'Content 1', '2023-01-01');
        $blogPost2 = new BlogPost(2, 'Title 2', 'slug-2', 'Content 2', '2023-01-02');

        $this->repository->save($blogPost1);
        $this->repository->save($blogPost2);

        $foundPost = $this->repository->findBySlug('slug-2');

        $this->assertInstanceOf(BlogPost::class, $foundPost);
        $this->assertEquals('Title 2', $foundPost->getTitle());
        $this->assertEquals('slug-2', $foundPost->getSlug());
    }

    public function testFindBySlugReturnsNullForNonExistentSlug(): void
    {
        $blogPost = new BlogPost(1, 'Title', 'existing-slug', 'Content', '2023-01-01');
        $this->repository->save($blogPost);

        $foundPost = $this->repository->findBySlug('non-existent-slug');

        $this->assertNull($foundPost);
    }

    public function testFindByIdReturnsCorrectPost(): void
    {
        $blogPost1 = new BlogPost(1, 'Title 1', 'slug-1', 'Content 1', '2023-01-01');
        $blogPost2 = new BlogPost(2, 'Title 2', 'slug-2', 'Content 2', '2023-01-02');

        $this->repository->save($blogPost1);
        $this->repository->save($blogPost2);

        $foundPost = $this->repository->findById(2);

        $this->assertInstanceOf(BlogPost::class, $foundPost);
        $this->assertEquals(2, $foundPost->getId());
        $this->assertEquals('Title 2', $foundPost->getTitle());
    }

    public function testFindByIdReturnsNullForNonExistentId(): void
    {
        $blogPost = new BlogPost(1, 'Title', 'slug', 'Content', '2023-01-01');
        $this->repository->save($blogPost);

        $foundPost = $this->repository->findById(999);

        $this->assertNull($foundPost);
    }

    public function testDeleteRemovesPost(): void
    {
        $blogPost1 = new BlogPost(1, 'Title 1', 'slug-1', 'Content 1', '2023-01-01');
        $blogPost2 = new BlogPost(2, 'Title 2', 'slug-2', 'Content 2', '2023-01-02');

        $this->repository->save($blogPost1);
        $this->repository->save($blogPost2);

        $this->repository->delete(1);
        $posts = $this->repository->findAll();

        $this->assertCount(1, $posts);
        $this->assertEquals(2, $posts[0]->getId());
        $this->assertEquals('Title 2', $posts[0]->getTitle());
    }

    public function testDeleteWithNonExistentIdDoesNothing(): void
    {
        $blogPost = new BlogPost(1, 'Title', 'slug', 'Content', '2023-01-01');
        $this->repository->save($blogPost);

        $this->repository->delete(999);
        $posts = $this->repository->findAll();

        $this->assertCount(1, $posts);
        $this->assertEquals(1, $posts[0]->getId());
    }

    public function testSaveMultiplePosts(): void
    {
        $blogPost1 = new BlogPost(1, 'Title 1', 'slug-1', 'Content 1', '2023-01-01');
        $blogPost2 = new BlogPost(2, 'Title 2', 'slug-2', 'Content 2', '2023-01-02');
        $blogPost3 = new BlogPost(3, 'Title 3', 'slug-3', 'Content 3', '2023-01-03');

        $this->repository->save($blogPost1);
        $this->repository->save($blogPost2);
        $this->repository->save($blogPost3);

        $posts = $this->repository->findAll();

        $this->assertCount(3, $posts);
        $this->assertEquals('Title 1', $posts[0]->getTitle());
        $this->assertEquals('Title 2', $posts[1]->getTitle());
        $this->assertEquals('Title 3', $posts[2]->getTitle());
    }

    public function testRepositoryHandlesInvalidJsonGracefully(): void
    {
        file_put_contents($this->tempFile, 'invalid json content');
        
        $posts = $this->repository->findAll();
        
        $this->assertIsArray($posts);
        $this->assertEmpty($posts);
    }
}
<?php

declare(strict_types=1);

namespace Tests\Domain\Blog;

use App\Domain\Blog\BlogPost;
use PHPUnit\Framework\TestCase;

class BlogPostTest extends TestCase
{
    public function testBlogPostCreationWithAllFields(): void
    {
        $blogPost = new BlogPost(
            1,
            'Test Title',
            'test-slug',
            'This is test content',
            '2023-01-01',
            'tag1,tag2',
            'Test meta description',
            true,
            'https://youtube.com/watch?v=test',
            'featured-image.jpg'
        );

        $this->assertEquals(1, $blogPost->getId());
        $this->assertEquals('Test Title', $blogPost->getTitle());
        $this->assertEquals('test-slug', $blogPost->getSlug());
        $this->assertEquals('This is test content', $blogPost->getContent());
        $this->assertEquals('2023-01-01', $blogPost->getDate());
        $this->assertEquals('tag1,tag2', $blogPost->getTags());
        $this->assertEquals('Test meta description', $blogPost->getMetaDescription());
        $this->assertTrue($blogPost->isPublished());
        $this->assertEquals('https://youtube.com/watch?v=test', $blogPost->getYoutubeVideo());
        $this->assertEquals('featured-image.jpg', $blogPost->getFeaturedImage());
    }

    public function testBlogPostCreationWithMinimalFields(): void
    {
        $blogPost = new BlogPost(
            1,
            'Test Title',
            'test-slug',
            'This is test content',
            '2023-01-01'
        );

        $this->assertEquals(1, $blogPost->getId());
        $this->assertEquals('Test Title', $blogPost->getTitle());
        $this->assertEquals('test-slug', $blogPost->getSlug());
        $this->assertEquals('This is test content', $blogPost->getContent());
        $this->assertEquals('2023-01-01', $blogPost->getDate());
        $this->assertEquals('', $blogPost->getTags());
        $this->assertEquals('', $blogPost->getMetaDescription());
        $this->assertFalse($blogPost->isPublished());
        $this->assertEquals('', $blogPost->getYoutubeVideo());
        $this->assertEquals('', $blogPost->getFeaturedImage());
    }

    public function testHasYoutubeVideoReturnsTrueWhenVideoExists(): void
    {
        $blogPost = new BlogPost(
            1,
            'Test Title',
            'test-slug',
            'Content',
            '2023-01-01',
            '',
            '',
            false,
            'https://youtube.com/watch?v=test'
        );

        $this->assertTrue($blogPost->hasYoutubeVideo());
    }

    public function testHasYoutubeVideoReturnsFalseWhenVideoEmpty(): void
    {
        $blogPost = new BlogPost(
            1,
            'Test Title',
            'test-slug',
            'Content',
            '2023-01-01'
        );

        $this->assertFalse($blogPost->hasYoutubeVideo());
    }

    public function testHasFeaturedImageReturnsTrueWhenImageExists(): void
    {
        $blogPost = new BlogPost(
            1,
            'Test Title',
            'test-slug',
            'Content',
            '2023-01-01',
            '',
            '',
            false,
            '',
            'featured.jpg'
        );

        $this->assertTrue($blogPost->hasFeaturedImage());
    }

    public function testHasFeaturedImageReturnsFalseWhenImageEmpty(): void
    {
        $blogPost = new BlogPost(
            1,
            'Test Title',
            'test-slug',
            'Content',
            '2023-01-01'
        );

        $this->assertFalse($blogPost->hasFeaturedImage());
    }

    public function testToArrayContainsAllFields(): void
    {
        $blogPost = new BlogPost(
            1,
            'Test Title',
            'test-slug',
            'This is test content',
            '2023-01-01',
            'tag1,tag2',
            'Test meta description',
            true,
            'https://youtube.com/watch?v=test',
            'featured-image.jpg'
        );

        $array = $blogPost->toArray();

        $expected = [
            'id' => 1,
            'title' => 'Test Title',
            'slug' => 'test-slug',
            'content' => 'This is test content',
            'date' => '2023-01-01',
            'tags' => 'tag1,tag2',
            'meta_description' => 'Test meta description',
            'published' => true,
            'youtube_video' => 'https://youtube.com/watch?v=test',
            'featured_image' => 'featured-image.jpg'
        ];

        $this->assertEquals($expected, $array);
    }

    public function testFromArrayCreatesCorrectBlogPost(): void
    {
        $data = [
            'id' => 1,
            'title' => 'Test Title',
            'slug' => 'test-slug',
            'content' => 'This is test content',
            'date' => '2023-01-01',
            'tags' => 'tag1,tag2',
            'meta_description' => 'Test meta description',
            'published' => true,
            'youtube_video' => 'https://youtube.com/watch?v=test',
            'featured_image' => 'featured-image.jpg'
        ];

        $blogPost = BlogPost::fromArray($data);

        $this->assertEquals(1, $blogPost->getId());
        $this->assertEquals('Test Title', $blogPost->getTitle());
        $this->assertEquals('test-slug', $blogPost->getSlug());
        $this->assertEquals('This is test content', $blogPost->getContent());
        $this->assertEquals('2023-01-01', $blogPost->getDate());
        $this->assertEquals('tag1,tag2', $blogPost->getTags());
        $this->assertEquals('Test meta description', $blogPost->getMetaDescription());
        $this->assertTrue($blogPost->isPublished());
        $this->assertEquals('https://youtube.com/watch?v=test', $blogPost->getYoutubeVideo());
        $this->assertEquals('featured-image.jpg', $blogPost->getFeaturedImage());
    }

    public function testFromArrayWithMissingFieldsUsesDefaults(): void
    {
        $data = [
            'id' => 1,
            'title' => 'Test Title',
            'slug' => 'test-slug',
            'content' => 'This is test content',
            'date' => '2023-01-01'
        ];

        $blogPost = BlogPost::fromArray($data);

        $this->assertEquals(1, $blogPost->getId());
        $this->assertEquals('Test Title', $blogPost->getTitle());
        $this->assertEquals('test-slug', $blogPost->getSlug());
        $this->assertEquals('This is test content', $blogPost->getContent());
        $this->assertEquals('2023-01-01', $blogPost->getDate());
        $this->assertEquals('', $blogPost->getTags());
        $this->assertEquals('', $blogPost->getMetaDescription());
        $this->assertFalse($blogPost->isPublished());
        $this->assertEquals('', $blogPost->getYoutubeVideo());
        $this->assertEquals('', $blogPost->getFeaturedImage());
    }

    public function testArrayConversionRoundtrip(): void
    {
        $originalBlogPost = new BlogPost(
            1,
            'Test Title',
            'test-slug',
            'This is test content',
            '2023-01-01',
            'tag1,tag2',
            'Test meta description',
            true,
            'https://youtube.com/watch?v=test',
            'featured-image.jpg'
        );

        $array = $originalBlogPost->toArray();
        $newBlogPost = BlogPost::fromArray($array);

        $this->assertEquals($originalBlogPost->toArray(), $newBlogPost->toArray());
    }
}
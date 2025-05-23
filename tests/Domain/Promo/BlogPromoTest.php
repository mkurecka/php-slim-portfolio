<?php

declare(strict_types=1);

namespace Tests\Domain\Promo;

use App\Domain\Promo\BlogPromo;
use PHPUnit\Framework\TestCase;

class BlogPromoTest extends TestCase
{
    public function testBlogPromoCreationWithAllFields(): void
    {
        $blogPromo = new BlogPromo(
            'Special Offer!',
            'Get 50% off on all courses',
            'https://example.com/promo',
            'Learn more'
        );

        $this->assertEquals('Special Offer!', $blogPromo->getTitle());
        $this->assertEquals('Get 50% off on all courses', $blogPromo->getDescription());
        $this->assertEquals('https://example.com/promo', $blogPromo->getUrl());
        $this->assertEquals('Learn more', $blogPromo->getButtonText());
    }

    public function testBlogPromoCreationWithMinimalFields(): void
    {
        $blogPromo = new BlogPromo(
            'Special Offer!',
            'Get 50% off on all courses'
        );

        $this->assertEquals('Special Offer!', $blogPromo->getTitle());
        $this->assertEquals('Get 50% off on all courses', $blogPromo->getDescription());
        $this->assertEquals('', $blogPromo->getUrl());
        $this->assertEquals('', $blogPromo->getButtonText());
    }

    public function testBlogPromoCreationWithEmptyStrings(): void
    {
        $blogPromo = new BlogPromo('', '');

        $this->assertEquals('', $blogPromo->getTitle());
        $this->assertEquals('', $blogPromo->getDescription());
        $this->assertEquals('', $blogPromo->getUrl());
        $this->assertEquals('', $blogPromo->getButtonText());
    }

    public function testToArrayContainsAllFields(): void
    {
        $blogPromo = new BlogPromo(
            'Special Offer!',
            'Get 50% off on all courses',
            'https://example.com/promo',
            'Learn more'
        );

        $array = $blogPromo->toArray();

        $expected = [
            'title' => 'Special Offer!',
            'description' => 'Get 50% off on all courses',
            'url' => 'https://example.com/promo',
            'button_text' => 'Learn more'
        ];

        $this->assertEquals($expected, $array);
    }

    public function testToArrayWithMinimalFields(): void
    {
        $blogPromo = new BlogPromo(
            'Special Offer!',
            'Get 50% off on all courses'
        );

        $array = $blogPromo->toArray();

        $expected = [
            'title' => 'Special Offer!',
            'description' => 'Get 50% off on all courses',
            'url' => '',
            'button_text' => ''
        ];

        $this->assertEquals($expected, $array);
    }

    public function testFromArrayCreatesCorrectBlogPromo(): void
    {
        $data = [
            'title' => 'Special Offer!',
            'description' => 'Get 50% off on all courses',
            'url' => 'https://example.com/promo',
            'button_text' => 'Learn more'
        ];

        $blogPromo = BlogPromo::fromArray($data);

        $this->assertEquals('Special Offer!', $blogPromo->getTitle());
        $this->assertEquals('Get 50% off on all courses', $blogPromo->getDescription());
        $this->assertEquals('https://example.com/promo', $blogPromo->getUrl());
        $this->assertEquals('Learn more', $blogPromo->getButtonText());
    }

    public function testFromArrayWithMissingFieldsUsesDefaults(): void
    {
        $data = [
            'title' => 'Special Offer!',
            'description' => 'Get 50% off on all courses'
        ];

        $blogPromo = BlogPromo::fromArray($data);

        $this->assertEquals('Special Offer!', $blogPromo->getTitle());
        $this->assertEquals('Get 50% off on all courses', $blogPromo->getDescription());
        $this->assertEquals('', $blogPromo->getUrl());
        $this->assertEquals('', $blogPromo->getButtonText());
    }

    public function testFromArrayWithEmptyArray(): void
    {
        $data = [];

        $blogPromo = BlogPromo::fromArray($data);

        $this->assertEquals('', $blogPromo->getTitle());
        $this->assertEquals('', $blogPromo->getDescription());
        $this->assertEquals('', $blogPromo->getUrl());
        $this->assertEquals('', $blogPromo->getButtonText());
    }

    public function testArrayConversionRoundtrip(): void
    {
        $originalBlogPromo = new BlogPromo(
            'Special Offer!',
            'Get 50% off on all courses',
            'https://example.com/promo',
            'Learn more'
        );

        $array = $originalBlogPromo->toArray();
        $newBlogPromo = BlogPromo::fromArray($array);

        $this->assertEquals($originalBlogPromo->toArray(), $newBlogPromo->toArray());
    }

    public function testArrayConversionRoundtripWithMinimalFields(): void
    {
        $originalBlogPromo = new BlogPromo(
            'Title Only',
            'Description Only'
        );

        $array = $originalBlogPromo->toArray();
        $newBlogPromo = BlogPromo::fromArray($array);

        $this->assertEquals($originalBlogPromo->toArray(), $newBlogPromo->toArray());
    }

    public function testFromArrayHandlesNullValues(): void
    {
        $data = [
            'title' => null,
            'description' => null,
            'url' => null,
            'button_text' => null
        ];

        $blogPromo = BlogPromo::fromArray($data);

        $this->assertEquals('', $blogPromo->getTitle());
        $this->assertEquals('', $blogPromo->getDescription());
        $this->assertEquals('', $blogPromo->getUrl());
        $this->assertEquals('', $blogPromo->getButtonText());
    }
}
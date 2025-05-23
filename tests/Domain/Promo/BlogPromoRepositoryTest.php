<?php

declare(strict_types=1);

namespace Tests\Domain\Promo;

use App\Domain\Promo\BlogPromo;
use App\Domain\Promo\BlogPromoRepository;
use PHPUnit\Framework\TestCase;

class BlogPromoRepositoryTest extends TestCase
{
    private string $tempFile;
    private BlogPromoRepository $repository;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'blog_promo_test_');
        $this->repository = new BlogPromoRepository($this->tempFile);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    public function testGetPromoReturnsDefaultForNonExistentFile(): void
    {
        unlink($this->tempFile);
        
        $promo = $this->repository->getPromo();
        
        $this->assertInstanceOf(BlogPromo::class, $promo);
        $this->assertEquals('Welcome to My Blog!', $promo->getTitle());
        $this->assertEquals('Stay updated with the latest posts and insights.', $promo->getDescription());
        $this->assertEquals('/blog', $promo->getUrl());
        $this->assertEquals('Read Blog', $promo->getButtonText());
    }

    public function testGetPromoReturnsDataFromExistingFile(): void
    {
        $testData = [
            'title' => 'Custom Promo Title',
            'description' => 'Custom promo description',
            'url' => 'https://custom.com',
            'button_text' => 'Custom Button'
        ];

        file_put_contents($this->tempFile, json_encode($testData));
        
        $promo = $this->repository->getPromo();
        
        $this->assertInstanceOf(BlogPromo::class, $promo);
        $this->assertEquals('Custom Promo Title', $promo->getTitle());
        $this->assertEquals('Custom promo description', $promo->getDescription());
        $this->assertEquals('https://custom.com', $promo->getUrl());
        $this->assertEquals('Custom Button', $promo->getButtonText());
    }

    public function testGetPromoHandlesInvalidJsonWithDefaults(): void
    {
        file_put_contents($this->tempFile, 'invalid json content');
        
        $promo = $this->repository->getPromo();
        
        $this->assertInstanceOf(BlogPromo::class, $promo);
        $this->assertEquals('Welcome to My Blog!', $promo->getTitle());
        $this->assertEquals('Stay updated with the latest posts and insights.', $promo->getDescription());
        $this->assertEquals('/blog', $promo->getUrl());
        $this->assertEquals('Read Blog', $promo->getButtonText());
    }

    public function testGetPromoHandlesPartialDataWithDefaults(): void
    {
        $partialData = [
            'title' => 'Custom Title Only'
        ];

        file_put_contents($this->tempFile, json_encode($partialData));
        
        $promo = $this->repository->getPromo();
        
        $this->assertEquals('Custom Title Only', $promo->getTitle());
        $this->assertEquals('Stay updated with the latest posts and insights.', $promo->getDescription());
        $this->assertEquals('/blog', $promo->getUrl());
        $this->assertEquals('Read Blog', $promo->getButtonText());
    }

    public function testSavePromoStoresDataCorrectly(): void
    {
        $promo = new BlogPromo(
            'New Promo Title',
            'New promo description',
            'https://newpromo.com',
            'New Button Text'
        );

        $this->repository->savePromo($promo);
        
        $this->assertFileExists($this->tempFile);
        $savedData = json_decode(file_get_contents($this->tempFile), true);
        
        $expected = [
            'title' => 'New Promo Title',
            'description' => 'New promo description',
            'url' => 'https://newpromo.com',
            'button_text' => 'New Button Text'
        ];
        
        $this->assertEquals($expected, $savedData);
    }

    public function testSavePromoOverwritesExistingData(): void
    {
        $originalData = [
            'title' => 'Original Title',
            'description' => 'Original description',
            'url' => 'https://original.com',
            'button_text' => 'Original Button'
        ];
        
        file_put_contents($this->tempFile, json_encode($originalData));
        
        $newPromo = new BlogPromo(
            'Updated Title',
            'Updated description',
            'https://updated.com',
            'Updated Button'
        );

        $this->repository->savePromo($newPromo);
        
        $savedData = json_decode(file_get_contents($this->tempFile), true);
        $expected = [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'url' => 'https://updated.com',
            'button_text' => 'Updated Button'
        ];
        
        $this->assertEquals($expected, $savedData);
    }

    public function testGetPromoAfterSavePromoRoundtrip(): void
    {
        $originalPromo = new BlogPromo(
            'Test Title',
            'Test description',
            'https://test.com',
            'Test Button'
        );

        $this->repository->savePromo($originalPromo);
        $retrievedPromo = $this->repository->getPromo();
        
        $this->assertEquals($originalPromo->toArray(), $retrievedPromo->toArray());
    }

    public function testSavePromoWithMinimalFields(): void
    {
        $promo = new BlogPromo(
            'Title Only',
            'Description Only'
        );

        $this->repository->savePromo($promo);
        
        $savedData = json_decode(file_get_contents($this->tempFile), true);
        $expected = [
            'title' => 'Title Only',
            'description' => 'Description Only',
            'url' => '',
            'button_text' => ''
        ];
        
        $this->assertEquals($expected, $savedData);
    }

    public function testSavePromoCreatesFileIfNotExists(): void
    {
        unlink($this->tempFile);
        $this->assertFileDoesNotExist($this->tempFile);
        
        $promo = new BlogPromo('Test Title', 'Test Description');
        $this->repository->savePromo($promo);
        
        $this->assertFileExists($this->tempFile);
        $savedData = json_decode(file_get_contents($this->tempFile), true);
        $this->assertEquals('Test Title', $savedData['title']);
        $this->assertEquals('Test Description', $savedData['description']);
    }

    public function testGetPromoDefaultValues(): void
    {
        unlink($this->tempFile);
        
        $promo = $this->repository->getPromo();
        
        $this->assertEquals('Welcome to My Blog!', $promo->getTitle());
        $this->assertEquals('Stay updated with the latest posts and insights.', $promo->getDescription());
        $this->assertEquals('/blog', $promo->getUrl());
        $this->assertEquals('Read Blog', $promo->getButtonText());
    }

    public function testGetPromoMergesWithDefaults(): void
    {
        $partialData = [
            'title' => 'Custom Title',
            'url' => 'https://custom.url'
        ];

        file_put_contents($this->tempFile, json_encode($partialData));
        
        $promo = $this->repository->getPromo();
        
        $this->assertEquals('Custom Title', $promo->getTitle());
        $this->assertEquals('Stay updated with the latest posts and insights.', $promo->getDescription());
        $this->assertEquals('https://custom.url', $promo->getUrl());
        $this->assertEquals('Read Blog', $promo->getButtonText());
    }
}
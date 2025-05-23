<?php

declare(strict_types=1);

namespace Tests\Domain\Partner;

use App\Domain\Partner\PartnerLink;
use App\Domain\Partner\PartnerLinkRepository;
use PHPUnit\Framework\TestCase;

class PartnerLinkRepositoryTest extends TestCase
{
    private string $tempFile;
    private string $tempDir;
    private PartnerLinkRepository $repository;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/partner_test_' . uniqid();
        mkdir($this->tempDir);
        $this->tempFile = $this->tempDir . '/partners.json';
        $this->repository = new PartnerLinkRepository($this->tempFile);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
        if (is_dir($this->tempDir)) {
            rmdir($this->tempDir);
        }
    }

    public function testFindAllReturnsEmptyArrayForEmptyRepository(): void
    {
        file_put_contents($this->tempFile, '[]');
        $partners = $this->repository->findAll();
        
        $this->assertIsArray($partners);
        $this->assertEmpty($partners);
    }

    public function testFindAllReturnsEmptyArrayForNonExistentFile(): void
    {
        unlink($this->tempFile);
        $partners = $this->repository->findAll();
        
        $this->assertIsArray($partners);
        $this->assertEmpty($partners);
    }

    public function testSaveAndFindAll(): void
    {
        $partnerLink = new PartnerLink(
            1,
            'Example Partner',
            'example-partner',
            'https://example.com',
            'Partner description',
            0
        );

        $this->repository->save($partnerLink);
        $partners = $this->repository->findAll();

        $this->assertCount(1, $partners);
        $this->assertInstanceOf(PartnerLink::class, $partners[0]);
        $this->assertEquals('Example Partner', $partners[0]->getName());
        $this->assertEquals('example-partner', $partners[0]->getSlug());
    }

    public function testSaveGeneratesIdForNewPartner(): void
    {
        $partnerLink = new PartnerLink(
            0,
            'Example Partner',
            'example-partner',
            'https://example.com'
        );

        $savedPartner = $this->repository->save($partnerLink);
        
        $this->assertGreaterThan(0, $savedPartner->getId());
        $this->assertEquals('Example Partner', $savedPartner->getName());
    }

    public function testSaveUpdatesExistingPartner(): void
    {
        $partnerLink = new PartnerLink(
            1,
            'Original Partner',
            'original-partner',
            'https://original.com'
        );

        $this->repository->save($partnerLink);

        $updatedPartner = new PartnerLink(
            1,
            'Updated Partner',
            'updated-partner',
            'https://updated.com',
            'Updated description',
            10
        );

        $this->repository->save($updatedPartner);
        $partners = $this->repository->findAll();

        $this->assertCount(1, $partners);
        $this->assertEquals('Updated Partner', $partners[0]->getName());
        $this->assertEquals('updated-partner', $partners[0]->getSlug());
        $this->assertEquals(10, $partners[0]->getClickCount());
    }

    public function testFindBySlugReturnsCorrectPartner(): void
    {
        $partner1 = new PartnerLink(1, 'Partner 1', 'partner-1', 'https://partner1.com');
        $partner2 = new PartnerLink(2, 'Partner 2', 'partner-2', 'https://partner2.com');

        $this->repository->save($partner1);
        $this->repository->save($partner2);

        $foundPartner = $this->repository->findBySlug('partner-2');

        $this->assertInstanceOf(PartnerLink::class, $foundPartner);
        $this->assertEquals('Partner 2', $foundPartner->getName());
        $this->assertEquals('partner-2', $foundPartner->getSlug());
    }

    public function testFindBySlugReturnsNullForNonExistentSlug(): void
    {
        $partner = new PartnerLink(1, 'Partner', 'existing-partner', 'https://example.com');
        $this->repository->save($partner);

        $foundPartner = $this->repository->findBySlug('non-existent-partner');

        $this->assertNull($foundPartner);
    }

    public function testFindByIdReturnsCorrectPartner(): void
    {
        $partner1 = new PartnerLink(1, 'Partner 1', 'partner-1', 'https://partner1.com');
        $partner2 = new PartnerLink(2, 'Partner 2', 'partner-2', 'https://partner2.com');

        $this->repository->save($partner1);
        $this->repository->save($partner2);

        $foundPartner = $this->repository->findById(2);

        $this->assertInstanceOf(PartnerLink::class, $foundPartner);
        $this->assertEquals(2, $foundPartner->getId());
        $this->assertEquals('Partner 2', $foundPartner->getName());
    }

    public function testFindByIdReturnsNullForNonExistentId(): void
    {
        $partner = new PartnerLink(1, 'Partner', 'partner', 'https://example.com');
        $this->repository->save($partner);

        $foundPartner = $this->repository->findById(999);

        $this->assertNull($foundPartner);
    }

    public function testDeleteRemovesPartner(): void
    {
        $partner1 = new PartnerLink(1, 'Partner 1', 'partner-1', 'https://partner1.com');
        $partner2 = new PartnerLink(2, 'Partner 2', 'partner-2', 'https://partner2.com');

        $this->repository->save($partner1);
        $this->repository->save($partner2);

        $this->repository->delete(1);
        $partners = $this->repository->findAll();

        $this->assertCount(1, $partners);
        $this->assertEquals(2, $partners[0]->getId());
        $this->assertEquals('Partner 2', $partners[0]->getName());
    }

    public function testDeleteWithNonExistentIdDoesNothing(): void
    {
        $partner = new PartnerLink(1, 'Partner', 'partner', 'https://example.com');
        $this->repository->save($partner);

        $this->repository->delete(999);
        $partners = $this->repository->findAll();

        $this->assertCount(1, $partners);
        $this->assertEquals(1, $partners[0]->getId());
    }

    public function testIncrementClickCountUpdatesClickCount(): void
    {
        $partner = new PartnerLink(1, 'Partner', 'partner', 'https://example.com', 'Description', 5);
        $this->repository->save($partner);

        $this->repository->incrementClickCount(1);
        $updatedPartner = $this->repository->findById(1);

        $this->assertEquals(6, $updatedPartner->getClickCount());
    }

    public function testIncrementClickCountWithNonExistentIdDoesNothing(): void
    {
        $partner = new PartnerLink(1, 'Partner', 'partner', 'https://example.com', 'Description', 5);
        $this->repository->save($partner);

        $this->repository->incrementClickCount(999);
        $unchangedPartner = $this->repository->findById(1);

        $this->assertEquals(5, $unchangedPartner->getClickCount());
    }

    public function testSlugExistsReturnsTrueForExistingSlug(): void
    {
        $partner = new PartnerLink(1, 'Partner', 'existing-slug', 'https://example.com');
        $this->repository->save($partner);

        $this->assertTrue($this->repository->slugExists('existing-slug'));
    }

    public function testSlugExistsReturnsFalseForNonExistentSlug(): void
    {
        $partner = new PartnerLink(1, 'Partner', 'existing-slug', 'https://example.com');
        $this->repository->save($partner);

        $this->assertFalse($this->repository->slugExists('non-existent-slug'));
    }

    public function testSlugExistsReturnsFalseForEmptyRepository(): void
    {
        $this->assertFalse($this->repository->slugExists('any-slug'));
    }

    public function testRepositoryCreatesDirectoryIfNotExists(): void
    {
        $newTempDir = sys_get_temp_dir() . '/partner_test_new_' . uniqid();
        $newTempFile = $newTempDir . '/partners.json';
        
        $this->assertDirectoryDoesNotExist($newTempDir);
        
        new PartnerLinkRepository($newTempFile);
        
        $this->assertDirectoryExists($newTempDir);
        $this->assertFileExists($newTempFile);
        
        unlink($newTempFile);
        rmdir($newTempDir);
    }

    public function testRepositoryHandlesInvalidJsonGracefully(): void
    {
        file_put_contents($this->tempFile, 'invalid json content');
        
        $partners = $this->repository->findAll();
        
        $this->assertIsArray($partners);
        $this->assertEmpty($partners);
    }
}
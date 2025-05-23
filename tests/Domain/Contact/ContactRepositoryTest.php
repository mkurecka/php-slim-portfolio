<?php

declare(strict_types=1);

namespace Tests\Domain\Contact;

use App\Domain\Contact\ContactRepository;
use App\Domain\Contact\ContactSubmission;
use PHPUnit\Framework\TestCase;

class ContactRepositoryTest extends TestCase
{
    private string $tempFile;
    private ContactRepository $repository;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'contact_test_');
        $this->repository = new ContactRepository($this->tempFile);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    public function testSaveAndGetAllSubmission(): void
    {
        $submission = new ContactSubmission(
            'John Doe',
            'john@example.com',
            'Test Subject',
            'Test message',
            'test-id-123',
            '2023-01-01 12:00:00',
            '192.168.1.1'
        );

        $this->repository->save($submission);
        $submissions = $this->repository->getAll();

        $this->assertCount(1, $submissions);
        $this->assertInstanceOf(ContactSubmission::class, $submissions[0]);
        $this->assertEquals('John Doe', $submissions[0]->getName());
        $this->assertEquals('john@example.com', $submissions[0]->getEmail());
    }

    public function testGetAllReturnsEmptyArrayForEmptyRepository(): void
    {
        file_put_contents($this->tempFile, '[]');
        $submissions = $this->repository->getAll();
        
        $this->assertIsArray($submissions);
        $this->assertEmpty($submissions);
    }

    public function testGetAllReturnsEmptyArrayForNonExistentFile(): void
    {
        unlink($this->tempFile);
        $submissions = $this->repository->getAll();
        
        $this->assertIsArray($submissions);
        $this->assertEmpty($submissions);
    }

    public function testSaveMultipleSubmissions(): void
    {
        $submission1 = new ContactSubmission(
            'John Doe',
            'john@example.com',
            'Subject 1',
            'Message 1',
            'id-1',
            '2023-01-01 12:00:00',
            '192.168.1.1'
        );

        $submission2 = new ContactSubmission(
            'Jane Smith',
            'jane@example.com',
            'Subject 2',
            'Message 2',
            'id-2',
            '2023-01-02 12:00:00',
            '192.168.1.2'
        );

        $this->repository->save($submission1);
        $this->repository->save($submission2);
        $submissions = $this->repository->getAll();

        $this->assertCount(2, $submissions);
        $this->assertEquals('John Doe', $submissions[0]->getName());
        $this->assertEquals('Jane Smith', $submissions[1]->getName());
    }

    public function testFindByIdReturnsCorrectSubmission(): void
    {
        $submission1 = new ContactSubmission(
            'John Doe',
            'john@example.com',
            'Subject 1',
            'Message 1',
            'id-1',
            '2023-01-01 12:00:00',
            '192.168.1.1'
        );

        $submission2 = new ContactSubmission(
            'Jane Smith',
            'jane@example.com',
            'Subject 2',
            'Message 2',
            'id-2',
            '2023-01-02 12:00:00',
            '192.168.1.2'
        );

        $this->repository->save($submission1);
        $this->repository->save($submission2);

        $foundSubmission = $this->repository->findById('id-2');

        $this->assertInstanceOf(ContactSubmission::class, $foundSubmission);
        $this->assertEquals('id-2', $foundSubmission->getId());
        $this->assertEquals('Jane Smith', $foundSubmission->getName());
    }

    public function testFindByIdReturnsNullForNonExistentId(): void
    {
        $submission = new ContactSubmission(
            'John Doe',
            'john@example.com',
            'Subject',
            'Message',
            'existing-id'
        );
        
        $this->repository->save($submission);

        $foundSubmission = $this->repository->findById('non-existent-id');

        $this->assertNull($foundSubmission);
    }

    public function testDeleteRemovesSubmission(): void
    {
        $submission1 = new ContactSubmission(
            'John Doe',
            'john@example.com',
            'Subject 1',
            'Message 1',
            'id-1'
        );

        $submission2 = new ContactSubmission(
            'Jane Smith',
            'jane@example.com',
            'Subject 2',
            'Message 2',
            'id-2'
        );

        $this->repository->save($submission1);
        $this->repository->save($submission2);

        $this->repository->delete('id-1');
        $submissions = $this->repository->getAll();

        $this->assertCount(1, $submissions);
        $this->assertEquals('id-2', $submissions[0]->getId());
        $this->assertEquals('Jane Smith', $submissions[0]->getName());
    }

    public function testDeleteWithNonExistentIdDoesNothing(): void
    {
        $submission = new ContactSubmission(
            'John Doe',
            'john@example.com',
            'Subject',
            'Message',
            'existing-id'
        );
        
        $this->repository->save($submission);

        $this->repository->delete('non-existent-id');
        $submissions = $this->repository->getAll();

        $this->assertCount(1, $submissions);
        $this->assertEquals('existing-id', $submissions[0]->getId());
    }

    public function testRepositoryCreatesFileIfNotExists(): void
    {
        unlink($this->tempFile);
        $this->assertFileDoesNotExist($this->tempFile);
        
        new ContactRepository($this->tempFile);
        
        $this->assertFileExists($this->tempFile);
        $this->assertEquals('[]', file_get_contents($this->tempFile));
    }

    public function testRepositoryHandlesInvalidJsonGracefully(): void
    {
        file_put_contents($this->tempFile, 'invalid json content');
        
        $submissions = $this->repository->getAll();
        
        $this->assertIsArray($submissions);
        $this->assertEmpty($submissions);
    }

    public function testGetAllSkipsInvalidSubmissionData(): void
    {
        $validSubmission = [
            'id' => 'valid-id',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Subject',
            'message' => 'Message',
            'date' => '2023-01-01 12:00:00',
            'ip' => '192.168.1.1'
        ];

        $invalidSubmission = [
            'id' => 'invalid-id'
        ];

        $data = [$validSubmission, $invalidSubmission, 'completely-invalid'];
        file_put_contents($this->tempFile, json_encode($data));

        $submissions = $this->repository->getAll();

        $this->assertCount(1, $submissions);
        $this->assertEquals('valid-id', $submissions[0]->getId());
        $this->assertEquals('John Doe', $submissions[0]->getName());
    }
}
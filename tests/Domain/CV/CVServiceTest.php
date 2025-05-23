<?php

declare(strict_types=1);

namespace Tests\Domain\CV;

use App\Domain\CV\CVService;
use PHPUnit\Framework\TestCase;

class CVServiceTest extends TestCase
{
    private string $tempFile;
    private CVService $service;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'cv_test_');
        $this->service = new CVService($this->tempFile);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    public function testGetCVReturnsEmptyArrayForNonExistentFile(): void
    {
        unlink($this->tempFile);
        
        $cvData = $this->service->getCV();
        
        $this->assertIsArray($cvData);
        $this->assertEmpty($cvData);
    }

    public function testGetCVReturnsDataFromExistingFile(): void
    {
        $testData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'experience' => [
                [
                    'company' => 'Tech Corp',
                    'position' => 'Developer',
                    'period' => '2020-2023'
                ]
            ],
            'skills' => ['PHP', 'JavaScript', 'MySQL']
        ];

        file_put_contents($this->tempFile, json_encode($testData));
        
        $cvData = $this->service->getCV();
        
        $this->assertEquals($testData, $cvData);
    }

    public function testGetCVHandlesInvalidJsonGracefully(): void
    {
        file_put_contents($this->tempFile, 'invalid json content');
        
        $cvData = $this->service->getCV();
        
        $this->assertIsArray($cvData);
        $this->assertEmpty($cvData);
    }

    public function testUpdateCVSavesDataCorrectly(): void
    {
        $testData = [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'experience' => [
                [
                    'company' => 'Startup Inc',
                    'position' => 'Senior Developer',
                    'period' => '2021-2024'
                ]
            ],
            'education' => [
                [
                    'institution' => 'University',
                    'degree' => 'Computer Science',
                    'year' => '2020'
                ]
            ]
        ];

        $this->service->updateCV($testData);
        
        $this->assertFileExists($this->tempFile);
        $savedData = json_decode(file_get_contents($this->tempFile), true);
        $this->assertEquals($testData, $savedData);
    }

    public function testUpdateCVOverwritesExistingData(): void
    {
        $originalData = [
            'name' => 'Original Name',
            'skills' => ['Old Skill']
        ];
        
        file_put_contents($this->tempFile, json_encode($originalData));
        
        $newData = [
            'name' => 'Updated Name',
            'skills' => ['New Skill 1', 'New Skill 2'],
            'experience' => ['New Experience']
        ];

        $this->service->updateCV($newData);
        
        $savedData = json_decode(file_get_contents($this->tempFile), true);
        $this->assertEquals($newData, $savedData);
        $this->assertNotEquals($originalData, $savedData);
    }

    public function testGetCVAfterUpdateCVRoundtrip(): void
    {
        $testData = [
            'personal' => [
                'name' => 'Test User',
                'location' => 'Test City',
                'phone' => '+1234567890'
            ],
            'summary' => 'Experienced developer with strong background in web technologies.',
            'skills' => [
                'Programming' => ['PHP', 'JavaScript', 'Python'],
                'Databases' => ['MySQL', 'PostgreSQL'],
                'Tools' => ['Git', 'Docker']
            ]
        ];

        $this->service->updateCV($testData);
        $retrievedData = $this->service->getCV();
        
        $this->assertEquals($testData, $retrievedData);
    }

    public function testUpdateCVWithEmptyArray(): void
    {
        $emptyData = [];

        $this->service->updateCV($emptyData);
        
        $this->assertFileExists($this->tempFile);
        $savedData = json_decode(file_get_contents($this->tempFile), true);
        $this->assertEquals($emptyData, $savedData);
    }

    public function testUpdateCVCreatesFileIfNotExists(): void
    {
        unlink($this->tempFile);
        $this->assertFileDoesNotExist($this->tempFile);
        
        $testData = ['name' => 'Test User'];
        $this->service->updateCV($testData);
        
        $this->assertFileExists($this->tempFile);
        $savedData = json_decode(file_get_contents($this->tempFile), true);
        $this->assertEquals($testData, $savedData);
    }
}
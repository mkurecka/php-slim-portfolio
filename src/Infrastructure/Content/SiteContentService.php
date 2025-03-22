<?php

namespace App\Infrastructure\Content;

class SiteContentService
{
    private string $contentFilePath;
    private array $content;
    private string $dataDir;
    private array $sectionFiles = [
        'blog_webhook' => 'blog_webhook.json'
    ];

    public function __construct(?string $dataDir = null)
    {
        $this->dataDir = $dataDir ?? __DIR__ . '/../../../data';
        $this->contentFilePath = $this->dataDir . '/site_content.json';
        $this->loadContent();
    }

    private function loadContent(): void
    {
        if (!file_exists($this->contentFilePath)) {
            throw new \RuntimeException("Site content file not found: {$this->contentFilePath}");
        }

        $jsonContent = file_get_contents($this->contentFilePath);
        $this->content = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Error decoding site content JSON: ' . json_last_error_msg());
        }
    }
    
    /**
     * Load content from a separate file for specific sections
     */
    private function loadSectionFile(string $section): array
    {
        if (!isset($this->sectionFiles[$section])) {
            return [];
        }
        
        $filePath = $this->dataDir . '/' . $this->sectionFiles[$section];
        
        if (!file_exists($filePath)) {
            return [];
        }
        
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }
        
        return $data;
    }
    
    /**
     * Save content to a separate file for specific sections
     */
    private function saveSectionFile(string $section, array $data): bool
    {
        if (!isset($this->sectionFiles[$section])) {
            return false;
        }
        
        $filePath = $this->dataDir . '/' . $this->sectionFiles[$section];
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        return file_put_contents($filePath, $jsonContent) !== false;
    }

    public function getAllContent(): array
    {
        return $this->content;
    }

    public function getContent(string $section): array
    {
        // Check if this section has a separate file
        if (isset($this->sectionFiles[$section])) {
            return $this->loadSectionFile($section);
        }
        
        $parts = explode('.', $section);
        $data = $this->content;

        foreach ($parts as $part) {
            if (!isset($data[$part])) {
                return [];
            }
            $data = $data[$part];
        }

        return $data;
    }

    public function updateContent(array $content): bool
    {
        $this->content = $content;
        return $this->saveContent();
    }

    public function updateSection(string $section, array $data): bool
    {
        // Check if this section has a separate file
        if (isset($this->sectionFiles[$section])) {
            return $this->saveSectionFile($section, $data);
        }
        
        $parts = explode('.', $section);
        $pointer = &$this->content;

        foreach ($parts as $part) {
            if (!isset($pointer[$part])) {
                $pointer[$part] = [];
            }
            $pointer = &$pointer[$part];
        }

        $pointer = $data;
        return $this->saveContent();
    }

    private function saveContent(): bool
    {
        $jsonContent = json_encode($this->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return file_put_contents($this->contentFilePath, $jsonContent) !== false;
    }
}

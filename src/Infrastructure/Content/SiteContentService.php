<?php

namespace App\Infrastructure\Content;

class SiteContentService
{
    private string $contentFilePath;
    private array $content;

    public function __construct(string $dataDir = null)
    {
        $this->contentFilePath = ($dataDir ?? __DIR__ . '/../../../data') . '/site_content.json';
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

    public function getAllContent(): array
    {
        return $this->content;
    }

    public function getContent(string $section): array
    {
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
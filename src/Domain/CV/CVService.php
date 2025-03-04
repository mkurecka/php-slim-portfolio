<?php

namespace App\Domain\CV;

class CVService
{
    private string $dataFile;

    public function __construct(?string $dataFile = null)
    {
        $this->dataFile = $dataFile ?? __DIR__ . '/../../../data/cv.json';
    }

    public function getCV(): array
    {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        
        $json = file_get_contents($this->dataFile);
        return json_decode($json, true) ?? [];
    }
    
    public function updateCV(array $cvData): void
    {
        $json = json_encode($cvData, JSON_PRETTY_PRINT);
        file_put_contents($this->dataFile, $json);
    }
}
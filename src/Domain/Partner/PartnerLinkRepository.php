<?php

namespace App\Domain\Partner;

class PartnerLinkRepository
{
    private string $dataFile;

    public function __construct(?string $dataFile = null)
    {
        $this->dataFile = $dataFile ?? __DIR__ . '/../../../data/partner_links.json';
    }

    public function findAll(): array
    {
        $data = $this->loadData();
        
        $partnerLinks = [];
        foreach ($data as $linkData) {
            $partnerLinks[] = PartnerLink::fromArray($linkData);
        }
        
        return $partnerLinks;
    }
    
    public function findBySlug(string $slug): ?PartnerLink
    {
        $links = $this->findAll();
        
        foreach ($links as $link) {
            if ($link->getSlug() === $slug) {
                return $link;
            }
        }
        
        return null;
    }
    
    public function findById(int $id): ?PartnerLink
    {
        $links = $this->findAll();
        
        foreach ($links as $link) {
            if ($link->getId() === $id) {
                return $link;
            }
        }
        
        return null;
    }
    
    public function save(PartnerLink $partnerLink): void
    {
        $data = $this->loadData();
        
        // Check if this is an update or new link
        $existingIndex = null;
        foreach ($data as $index => $link) {
            if ($link['id'] === $partnerLink->getId()) {
                $existingIndex = $index;
                break;
            }
        }
        
        if ($existingIndex !== null) {
            // Update existing link
            $data[$existingIndex] = $partnerLink->toArray();
        } else {
            // Add new link with next ID
            $nextId = 1;
            if (!empty($data)) {
                $ids = array_column($data, 'id');
                $nextId = max($ids) + 1;
            }
            
            $linkArray = $partnerLink->toArray();
            $linkArray['id'] = $nextId;
            $data[] = $linkArray;
        }
        
        $this->saveData($data);
    }
    
    public function delete(int $id): bool
    {
        $data = $this->loadData();
        
        foreach ($data as $index => $link) {
            if ($link['id'] === $id) {
                array_splice($data, $index, 1);
                $this->saveData($data);
                return true;
            }
        }
        
        return false;
    }
    
    public function incrementClickCount(string $slug): bool
    {
        $link = $this->findBySlug($slug);
        
        if (!$link) {
            return false;
        }
        
        $link->incrementClickCount();
        $this->save($link);
        
        return true;
    }
    
    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $links = $this->findAll();
        
        foreach ($links as $link) {
            if ($link->getSlug() === $slug && ($excludeId === null || $link->getId() !== $excludeId)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function loadData(): array
    {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        
        $json = file_get_contents($this->dataFile);
        return json_decode($json, true) ?? [];
    }
    
    private function saveData(array $data): void
    {
        $directory = dirname($this->dataFile);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->dataFile, $json);
    }
}

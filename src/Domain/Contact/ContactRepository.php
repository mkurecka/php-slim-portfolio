<?php

namespace App\Domain\Contact;

class ContactRepository
{
    private string $filePath;

    public function __construct(?string $dataDir = null)
    {
        $this->filePath = ($dataDir ?? __DIR__ . '/../../../data') . '/contact_submissions.json';
        
        // Create the file if it doesn't exist
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    /**
     * Save a new contact form submission
     */
    public function save(ContactSubmission $submission): bool
    {
        $submissions = $this->getAll();
        $submissions[] = $submission->toArray();
        
        return $this->saveData($submissions);
    }
    
    /**
     * Get all contact form submissions
     * 
     * @return ContactSubmission[]
     */
    public function getAll(): array
    {
        $data = $this->loadData();
        
        $submissions = [];
        foreach ($data as $item) {
            // Skip empty items or items without required fields
            if (empty($item) || !isset($item['name'], $item['email'], $item['subject'], $item['message'])) {
                continue;
            }
            $submissions[] = ContactSubmission::fromArray($item);
        }
        
        return $submissions;
    }
    
    /**
     * Get a specific submission by ID
     */
    public function findById(string $id): ?ContactSubmission
    {
        $data = $this->loadData();
        
        foreach ($data as $item) {
            // Skip empty items or items without required fields
            if (empty($item) || !isset($item['name'], $item['email'], $item['subject'], $item['message'])) {
                continue;
            }
            
            if (isset($item['id']) && $item['id'] === $id) {
                return ContactSubmission::fromArray($item);
            }
        }
        
        return null;
    }
    
    /**
     * Delete a submission by ID
     */
    public function delete(string $id): bool
    {
        $data = $this->loadData();
        $newData = [];
        
        foreach ($data as $item) {
            if ($item['id'] !== $id) {
                $newData[] = $item;
            }
        }
        
        return $this->saveData($newData);
    }
    
    /**
     * Load all submissions from file
     */
    private function loadData(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }
        
        $content = file_get_contents($this->filePath);
        $data = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Error decoding contact submissions JSON: ' . json_last_error_msg());
        }
        
        return $data ?? [];
    }
    
    /**
     * Save all submissions to file
     */
    private function saveData(array $data): bool
    {
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return file_put_contents($this->filePath, $jsonContent) !== false;
    }
}
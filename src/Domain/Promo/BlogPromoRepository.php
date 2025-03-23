<?php

namespace App\Domain\Promo;

class BlogPromoRepository
{
    private string $dataFile;

    public function __construct(?string $dataFile = null)
    {
        $this->dataFile = $dataFile ?? __DIR__ . '/../../../data/blog_promo.json';
    }

    public function getPromo(): BlogPromo
    {
        $data = $this->loadData();
        return BlogPromo::fromArray($data);
    }
    
    public function savePromo(BlogPromo $promo): void
    {
        $this->saveData($promo->toArray());
    }
    
    private function loadData(): array
    {
        if (!file_exists($this->dataFile)) {
            return [
                'content' => '',
                'enabled' => false
            ];
        }
        
        $json = file_get_contents($this->dataFile);
        return json_decode($json, true) ?? [
            'content' => '',
            'enabled' => false
        ];
    }
    
    private function saveData(array $data): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->dataFile, $json);
    }
}

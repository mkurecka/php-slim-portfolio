<?php

namespace App\Domain\Partner;

class PartnerLink
{
    private int $id;
    private string $slug;
    private string $targetUrl;
    private string $description;
    private string $createdAt;
    private int $clickCount;

    public function __construct(
        int $id,
        string $slug,
        string $targetUrl,
        string $description,
        string $createdAt,
        int $clickCount = 0
    ) {
        $this->id = $id;
        $this->slug = $slug;
        $this->targetUrl = $targetUrl;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->clickCount = $clickCount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getTargetUrl(): string
    {
        return $this->targetUrl;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getClickCount(): int
    {
        return $this->clickCount;
    }

    public function incrementClickCount(): void
    {
        $this->clickCount++;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'targetUrl' => $this->targetUrl,
            'description' => $this->description,
            'createdAt' => $this->createdAt,
            'clickCount' => $this->clickCount,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? 0,
            $data['slug'] ?? '',
            $data['targetUrl'] ?? '',
            $data['description'] ?? '',
            $data['createdAt'] ?? date('Y-m-d H:i:s'),
            $data['clickCount'] ?? 0
        );
    }
}

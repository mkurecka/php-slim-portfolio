<?php

namespace App\Domain\Promo;

class BlogPromo
{
    private string $content;
    private bool $enabled;

    public function __construct(string $content, bool $enabled)
    {
        $this->content = $content;
        $this->enabled = $enabled;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'enabled' => $this->enabled,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['content'] ?? '',
            $data['enabled'] ?? false
        );
    }
}

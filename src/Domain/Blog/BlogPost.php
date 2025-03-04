<?php

namespace App\Domain\Blog;

class BlogPost
{
    private int $id;
    private string $title;
    private string $slug;
    private string $date;
    private string $excerpt;
    private string $content;
    private array $tags;
    private ?string $youtube_url;

    public function __construct(
        int $id,
        string $title,
        string $slug,
        string $date,
        string $excerpt,
        string $content,
        array $tags,
        ?string $youtube_url = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->date = $date;
        $this->excerpt = $excerpt;
        $this->content = $content;
        $this->tags = $tags;
        $this->youtube_url = $youtube_url;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    public function getContent(): string
    {
        return $this->content;
    }
    
    public function getRawContent(): string
    {
        return $this->content;
    }
    
    public function getYoutubeUrl(): ?string
    {
        return $this->youtube_url;
    }
    
    public function hasYoutubeVideo(): bool
    {
        return !empty($this->youtube_url);
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'date' => $this->date,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'tags' => $this->tags,
            'youtube_url' => $this->youtube_url,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? 0,
            $data['title'] ?? '',
            $data['slug'] ?? '',
            $data['date'] ?? date('Y-m-d'),
            $data['excerpt'] ?? '',
            $data['content'] ?? '',
            $data['tags'] ?? [],
            $data['youtube_url'] ?? null
        );
    }
}
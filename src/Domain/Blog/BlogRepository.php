<?php

namespace App\Domain\Blog;

class BlogRepository
{
    private string $dataFile;

    public function __construct(?string $dataFile = null)
    {
        $this->dataFile = $dataFile ?? __DIR__ . '/../../../data/blog_posts.json';
    }

    public function findAll(): array
    {
        $data = $this->loadData();
        
        $blogPosts = [];
        foreach ($data as $postData) {
            $blogPosts[] = BlogPost::fromArray($postData);
        }
        
        return $blogPosts;
    }
    
    public function findBySlug(string $slug): ?BlogPost
    {
        $posts = $this->findAll();
        
        foreach ($posts as $post) {
            if ($post->getSlug() === $slug) {
                return $post;
            }
        }
        
        return null;
    }
    
    public function findById(int $id): ?BlogPost
    {
        $posts = $this->findAll();
        
        foreach ($posts as $post) {
            if ($post->getId() === $id) {
                return $post;
            }
        }
        
        return null;
    }
    
    public function save(BlogPost $blogPost): void
    {
        $data = $this->loadData();
        
        // Check if this is an update or new post
        $existingIndex = null;
        foreach ($data as $index => $post) {
            if ($post['id'] === $blogPost->getId()) {
                $existingIndex = $index;
                break;
            }
        }
        
        if ($existingIndex !== null) {
            // Update existing post
            $data[$existingIndex] = $blogPost->toArray();
        } else {
            // Add new post with next ID
            $nextId = 1;
            if (!empty($data)) {
                $ids = array_column($data, 'id');
                $nextId = max($ids) + 1;
            }
            
            $postArray = $blogPost->toArray();
            $postArray['id'] = $nextId;
            $data[] = $postArray;
        }
        
        $this->saveData($data);
    }
    
    public function delete(int $id): bool
    {
        $data = $this->loadData();
        
        foreach ($data as $index => $post) {
            if ($post['id'] === $id) {
                array_splice($data, $index, 1);
                $this->saveData($data);
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
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->dataFile, $json);
    }
}
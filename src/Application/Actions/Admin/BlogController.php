<?php

namespace App\Application\Actions\Admin;

use App\Domain\Blog\BlogPost;
use App\Domain\Blog\BlogRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class BlogController
{
    private PhpRenderer $renderer;
    private BlogRepository $blogRepository;

    public function __construct(PhpRenderer $renderer, BlogRepository $blogRepository)
    {
        $this->renderer = $renderer;
        $this->blogRepository = $blogRepository;
    }

    public function index(Request $request, Response $response): Response
    {
        $posts = $this->blogRepository->findAll();
        
        return $this->renderer->render($response, 'admin/blog/index.php', [
            'title' => 'Manage Blog Posts',
            'posts' => $posts
        ]);
    }
    
    public function newPost(Request $request, Response $response): Response
    {
        return $this->renderer->render($response, 'admin/blog/form.php', [
            'title' => 'New Blog Post'
        ]);
    }
    
    public function createPost(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        // Process tags
        $tags = [];
        if (!empty($data['tags'])) {
            $tags = array_map('trim', explode(',', $data['tags']));
        }
        
        $post = new BlogPost(
            0, // ID will be set by repository
            $data['title'],
            $data['slug'],
            $data['date'],
            $data['excerpt'],
            $data['content'],
            $tags,
            $data['youtube_url'] ?? null
        );
        
        $this->blogRepository->save($post);
        
        return $response->withHeader('Location', '/admin/blog')
                        ->withStatus(302);
    }
    
    public function editPost(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $post = $this->blogRepository->findById($id);
        
        if (!$post) {
            return $response->withStatus(404);
        }
        
        return $this->renderer->render($response, 'admin/blog/form.php', [
            'title' => 'Edit Blog Post',
            'post' => $post
        ]);
    }
    
    public function updatePost(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $post = $this->blogRepository->findById($id);
        
        if (!$post) {
            return $response->withStatus(404);
        }
        
        $data = $request->getParsedBody();
        
        // Process tags
        $tags = [];
        if (!empty($data['tags'])) {
            $tags = array_map('trim', explode(',', $data['tags']));
        }
        
        $updatedPost = new BlogPost(
            $id,
            $data['title'],
            $data['slug'],
            $data['date'],
            $data['excerpt'],
            $data['content'],
            $tags,
            $data['youtube_url'] ?? null
        );
        
        $this->blogRepository->save($updatedPost);
        
        return $response->withHeader('Location', '/admin/blog')
                        ->withStatus(302);
    }
    
    public function deletePost(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $this->blogRepository->delete($id);
        
        return $response->withHeader('Location', '/admin/blog')
                        ->withStatus(302);
    }
}
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

    private string $uploadsDir;

    public function __construct(PhpRenderer $renderer, BlogRepository $blogRepository)
    {
        $this->renderer = $renderer;
        $this->blogRepository = $blogRepository;
        $this->uploadsDir = __DIR__ . '/../../../../data/images';
        
        // Create uploads directory if it doesn't exist
        if (!is_dir($this->uploadsDir)) {
            mkdir($this->uploadsDir, 0755, true);
        }
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
        $uploadedFiles = $request->getUploadedFiles();
        
        // Process tags
        $tags = [];
        if (!empty($data['tags'])) {
            $tags = array_map('trim', explode(',', $data['tags']));
        }
        
        // Process featured image
        $featuredImage = null;
        
        // Check if an image URL was provided
        if (!empty($data['featured_image_url'])) {
            $featuredImage = $this->processExternalImage($data['featured_image_url'], $data['slug']);
        } 
        // Check if an image was uploaded
        elseif (isset($uploadedFiles['featured_image']) && $uploadedFiles['featured_image']->getError() === UPLOAD_ERR_OK) {
            $featuredImage = $this->processUploadedImage($uploadedFiles['featured_image'], $data['slug']);
        }
        
        $post = new BlogPost(
            0, // ID will be set by repository
            $data['title'],
            $data['slug'],
            $data['date'],
            $data['excerpt'],
            $data['content'],
            $tags,
            $data['youtube_url'] ?? null,
            $featuredImage
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
        $uploadedFiles = $request->getUploadedFiles();
        
        // Process tags
        $tags = [];
        if (!empty($data['tags'])) {
            $tags = array_map('trim', explode(',', $data['tags']));
        }
        
        // Process featured image
        $featuredImage = $post->getFeaturedImage();
        
        // Check if remove image was checked
        if (isset($data['remove_featured_image']) && $data['remove_featured_image'] === 'on') {
            $featuredImage = null;
            // Delete the file if it's a local file
            if ($post->hasFeaturedImage() && strpos($post->getFeaturedImage(), '/data/images/') !== false) {
                $filePath = __DIR__ . '/../../../../' . ltrim($post->getFeaturedImage(), '/');
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        } 
        // Check if an image URL was provided
        elseif (!empty($data['featured_image_url'])) {
            $featuredImage = $this->processExternalImage($data['featured_image_url'], $data['slug']);
        } 
        // Check if an image was uploaded
        elseif (isset($uploadedFiles['featured_image']) && $uploadedFiles['featured_image']->getError() === UPLOAD_ERR_OK) {
            // Delete the old image if it exists and is a local file
            if ($post->hasFeaturedImage() && strpos($post->getFeaturedImage(), '/data/images/') !== false) {
                $filePath = __DIR__ . '/../../../../' . ltrim($post->getFeaturedImage(), '/');
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $featuredImage = $this->processUploadedImage($uploadedFiles['featured_image'], $data['slug']);
        }
        
        $updatedPost = new BlogPost(
            $id,
            $data['title'],
            $data['slug'],
            $data['date'],
            $data['excerpt'],
            $data['content'],
            $tags,
            $data['youtube_url'] ?? null,
            $featuredImage
        );
        
        $this->blogRepository->save($updatedPost);
        
        return $response->withHeader('Location', '/admin/blog')
                        ->withStatus(302);
    }
    
    public function deletePost(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $post = $this->blogRepository->findById($id);
        
        // Delete the featured image if it exists and is a local file
        if ($post && $post->hasFeaturedImage() && strpos($post->getFeaturedImage(), '/data/images/') !== false) {
            $filePath = __DIR__ . '/../../../../' . ltrim($post->getFeaturedImage(), '/');
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $this->blogRepository->delete($id);
        
        return $response->withHeader('Location', '/admin/blog')
                        ->withStatus(302);
    }
    
    /**
     * Process an uploaded image file
     */
    private function processUploadedImage($uploadedFile, string $slug): string
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s-%s.%s', $slug, $basename, $extension);
        
        $uploadedFile->moveTo($this->uploadsDir . DIRECTORY_SEPARATOR . $filename);
        
        return '/data/images/' . $filename;
    }
    
    /**
     * Process an external image URL
     */
    private function processExternalImage(string $imageUrl, string $slug): string
    {
        // Check if the URL is valid
        if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return $imageUrl; // Return as is if not a valid URL
        }
        
        // If the URL is from our own domain, just return it
        $host = parse_url($imageUrl, PHP_URL_HOST);
        if ($host === $_SERVER['HTTP_HOST']) {
            return $imageUrl;
        }
        
        // Download the image
        $imageData = @file_get_contents($imageUrl);
        if ($imageData === false) {
            return $imageUrl; // Return original URL if download fails
        }
        
        // Determine file extension from content type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageData);
        
        // Determine extension based on mime type
        switch ($mimeType) {
            case 'image/jpeg':
                $extension = 'jpg';
                break;
            case 'image/png':
                $extension = 'png';
                break;
            case 'image/gif':
                $extension = 'gif';
                break;
            case 'image/webp':
                $extension = 'webp';
                break;
            default:
                $extension = 'jpg';
        }
        
        // Generate a unique filename
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s-%s.%s', $slug, $basename, $extension);
        $filePath = $this->uploadsDir . DIRECTORY_SEPARATOR . $filename;
        
        // Save the image
        file_put_contents($filePath, $imageData);
        
        return '/data/images/' . $filename;
    }
}

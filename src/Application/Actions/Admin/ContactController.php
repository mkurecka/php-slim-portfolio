<?php

namespace App\Application\Actions\Admin;

use App\Domain\Contact\ContactRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class ContactController
{
    private PhpRenderer $renderer;
    private ContactRepository $contactRepository;

    public function __construct(PhpRenderer $adminRenderer, ContactRepository $contactRepository)
    {
        $this->renderer = $adminRenderer;
        $this->contactRepository = $contactRepository;
    }

    public function index(Request $request, Response $response): Response
    {
        $submissions = $this->contactRepository->getAll();
        
        // Sort by date (newest first)
        usort($submissions, function($a, $b) {
            return strtotime($b->getDate()) - strtotime($a->getDate());
        });
        
        return $this->renderer->render($response, 'admin/contact/index.php', [
            'title' => 'Contact Submissions | Admin',
            'submissions' => $submissions
        ]);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'] ?? '';
        
        if (empty($id)) {
            return $response->withHeader('Location', '/admin/contact')->withStatus(302);
        }
        
        $this->contactRepository->delete($id);
        
        return $response->withHeader('Location', '/admin/contact?deleted=1')->withStatus(302);
    }
}
<?php

namespace App\Application\Actions;

use App\Infrastructure\Content\SiteContentService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class ContactAction extends BaseAction
{
    public function __construct(PhpRenderer $renderer, SiteContentService $contentService)
    {
        parent::__construct($renderer, $contentService);
    }

    public function showForm(Request $request, Response $response): Response
    {
        $globalContent = $this->contentService->getContent('global');
        $contactContent = $this->contentService->getContent('contact_page');
        $contactInfo = $this->contentService->getContent('contact');
        
        return $this->renderWithContent($response, 'contact.php', [
            'title' => 'Contact | ' . ($globalContent['site_name'] ?? 'Michal Kurecka'),
            'content' => $contactContent,
            'contact_info' => $contactInfo
        ]);
    }
    
    public function handleForm(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        $globalContent = $this->contentService->getContent('global');
        $contactContent = $this->contentService->getContent('contact_page');
        $contactInfo = $this->contentService->getContent('contact');
        
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $subject = $data['subject'] ?? '';
        $message = $data['message'] ?? '';
        
        // Validate form
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            return $this->renderWithContent($response, 'contact.php', [
                'title' => 'Contact | ' . ($globalContent['site_name'] ?? 'Michal Kurecka'),
                'error' => 'All fields are required.',
                'content' => $contactContent,
                'contact_info' => $contactInfo
            ]);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->renderWithContent($response, 'contact.php', [
                'title' => 'Contact | ' . ($globalContent['site_name'] ?? 'Michal Kurecka'),
                'error' => 'Please enter a valid email address.',
                'content' => $contactContent,
                'contact_info' => $contactInfo
            ]);
        }
        
        // In a real application, you would send the email here
        // For demonstration, we'll just return a success message
        
        // Build message for email
        $emailContent = "Name: $name\n";
        $emailContent .= "Email: $email\n";
        $emailContent .= "Subject: $subject\n\n";
        $emailContent .= $message;
        
        // Attempt to send email (commented out for demo)
        // mail($contactInfo['email'], "Contact Form: $subject", $emailContent, "From: $email");
        
        // Get success message from content
        $successMessage = $contactContent['form']['success'] ?? 'Your message has been sent. I\'ll get back to you soon!';
        
        return $this->renderWithContent($response, 'contact.php', [
            'title' => 'Contact | ' . ($globalContent['site_name'] ?? 'Michal Kurecka'),
            'success' => $successMessage,
            'content' => $contactContent,
            'contact_info' => $contactInfo
        ]);
    }
}
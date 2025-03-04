<?php

namespace App\Application\Actions;

use App\Domain\Contact\ContactRepository;
use App\Domain\Contact\ContactSubmission;
use App\Infrastructure\Content\SiteContentService;
use App\Infrastructure\Webhook\WebhookService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class ContactAction extends BaseAction
{
    private ContactRepository $contactRepository;
    private WebhookService $webhookService;

    public function __construct(
        PhpRenderer $renderer, 
        SiteContentService $contentService,
        ContactRepository $contactRepository,
        WebhookService $webhookService
    ) {
        parent::__construct($renderer, $contentService);
        $this->contactRepository = $contactRepository;
        $this->webhookService = $webhookService;
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
        
        // Create and save the contact submission
        $submission = new ContactSubmission($name, $email, $subject, $message);
        $saveSuccess = $this->contactRepository->save($submission);
        
        if (!$saveSuccess) {
            return $this->renderWithContent($response, 'contact.php', [
                'title' => 'Contact | ' . ($globalContent['site_name'] ?? 'Michal Kurecka'),
                'error' => 'There was an error saving your message. Please try again later.',
                'content' => $contactContent,
                'contact_info' => $contactInfo
            ]);
        }
        
        // Send to webhook if enabled
        $this->webhookService->sendContactSubmission($submission);
        // Note: We don't check the webhook result as we don't want to fail the form submission
        // if the webhook fails. The webhook failures are logged.
        
        // Build email content (for reference, not sending)
        $emailContent = "Name: $name\n";
        $emailContent .= "Email: $email\n";
        $emailContent .= "Subject: $subject\n\n";
        $emailContent .= $message;
        
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
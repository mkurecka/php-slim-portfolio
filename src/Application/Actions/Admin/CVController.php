<?php

namespace App\Application\Actions\Admin;

use App\Domain\CV\CVService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class CVController
{
    private PhpRenderer $renderer;
    private CVService $cvService;

    public function __construct(PhpRenderer $renderer, CVService $cvService)
    {
        $this->renderer = $renderer;
        $this->cvService = $cvService;
    }

    public function edit(Request $request, Response $response): Response
    {
        $cv = $this->cvService->getCV();
        
        return $this->renderer->render($response, 'admin/cv/edit.php', [
            'title' => 'Edit CV',
            'cv' => $cv
        ]);
    }
    
    public function update(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $cv = $this->cvService->getCV();
        
        // Update basic information
        $cv['name'] = $data['name'] ?? $cv['name'];
        $cv['title'] = $data['title'] ?? $cv['title'];
        $cv['summary'] = $data['summary'] ?? $cv['summary'];
        
        // Update contact information
        if (isset($data['contact'])) {
            foreach ($data['contact'] as $key => $value) {
                $cv['contact'][$key] = $value;
            }
        }
        
        // Update skills
        if (isset($data['skills'])) {
            foreach ($data['skills'] as $index => $skill) {
                $cv['skills'][$index]['category'] = $skill['category'];
                $cv['skills'][$index]['items'] = array_map('trim', explode(',', $skill['items']));
            }
        }
        
        // Update work experience
        if (isset($data['experience'])) {
            foreach ($data['experience'] as $index => $job) {
                $cv['experience'][$index]['position'] = $job['position'];
                $cv['experience'][$index]['company'] = $job['company'];
                $cv['experience'][$index]['location'] = $job['location'];
                $cv['experience'][$index]['period'] = $job['period'];
                $cv['experience'][$index]['description'] = $job['description'];
                
                // Process achievements (split by new line)
                $cv['experience'][$index]['achievements'] = array_filter(array_map('trim', explode("\n", $job['achievements'])));
            }
        }
        
        // Update education
        if (isset($data['education'])) {
            foreach ($data['education'] as $index => $edu) {
                $cv['education'][$index]['degree'] = $edu['degree'];
                $cv['education'][$index]['institution'] = $edu['institution'];
                $cv['education'][$index]['location'] = $edu['location'];
                $cv['education'][$index]['year'] = $edu['year'];
                $cv['education'][$index]['details'] = $edu['details'];
            }
        }
        
        // Save updated CV
        $this->cvService->updateCV($cv);
        
        // Return to CV edit page with success message
        return $this->renderer->render($response, 'admin/cv/edit.php', [
            'title' => 'Edit CV',
            'cv' => $cv,
            'success' => 'CV has been updated successfully.'
        ]);
    }
}
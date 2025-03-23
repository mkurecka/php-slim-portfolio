<?php

namespace App\Application\Actions\Admin;

use App\Domain\Promo\BlogPromo;
use App\Domain\Promo\BlogPromoRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class PromoController
{
    private PhpRenderer $renderer;
    private BlogPromoRepository $promoRepository;

    public function __construct(PhpRenderer $renderer, BlogPromoRepository $promoRepository)
    {
        $this->renderer = $renderer;
        $this->promoRepository = $promoRepository;
    }

    public function edit(Request $request, Response $response): Response
    {
        $promo = $this->promoRepository->getPromo();
        
        return $this->renderer->render($response, 'admin/promo/edit.php', [
            'title' => 'Edit Blog Promo Box',
            'promo' => $promo
        ]);
    }
    
    public function update(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        $enabled = isset($data['enabled']) && $data['enabled'] === 'on';
        $content = $data['content'] ?? '';
        
        $promo = new BlogPromo($content, $enabled);
        $this->promoRepository->savePromo($promo);
        
        return $response->withHeader('Location', '/admin/promo')
                        ->withStatus(302);
    }
}

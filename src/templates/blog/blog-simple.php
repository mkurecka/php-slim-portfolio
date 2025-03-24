<section class="py-10 bg-white">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="mb-6">
            <a href="/blog" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Blog
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php if ($post->hasFeaturedImage()): ?>
            <div class="w-full">
                <img src="<?= htmlspecialchars($post->getFeaturedImage()) ?>" alt="<?= htmlspecialchars($post->getTitle()) ?>" class="w-full h-auto">
            </div>
            <?php endif; ?>
            
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($post->getTitle()) ?></h1>
                
                <div class="flex items-center text-gray-500 text-sm mb-6">
                    <span><?= htmlspecialchars($post->getDate()) ?></span>
                    <span class="mx-2">•</span>
                    <span>
                        <?php foreach ($post->getTags() as $tag): ?>
                            <span class="inline-block bg-gray-100 rounded-full px-3 py-1 text-xs font-semibold text-gray-600 mr-2"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </span>
                </div>
                
                <div class="prose max-w-none">
                    <?= $markdownService->convertToHtml($post->getRawContent()) ?>
                </div>
                
                <?php if ($post->hasYoutubeVideo()): ?>
                <div class="mt-8">
                    <h3 class="text-xl font-bold mb-4">Watch Video</h3>
                    <div class="relative w-full rounded-lg overflow-hidden" style="padding-top: 56.25%">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full"
                            src="<?= str_replace('watch?v=', 'embed/', htmlspecialchars($post->getYoutubeUrl())) ?>" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Share this post</h3>
                    <div class="flex space-x-4">
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode('https://' . $_SERVER['HTTP_HOST'] . '/blog/' . $post->getSlug()) ?>&text=<?= urlencode($post->getTitle()) ?>" target="_blank" class="flex items-center text-gray-700 hover:text-blue-500 transition-colors">
                            <i class="fab fa-twitter text-lg mr-2"></i>
                            <span>Twitter</span>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode('https://' . $_SERVER['HTTP_HOST'] . '/blog/' . $post->getSlug()) ?>&title=<?= urlencode($post->getTitle()) ?>" target="_blank" class="flex items-center text-gray-700 hover:text-blue-700 transition-colors">
                            <i class="fab fa-linkedin text-lg mr-2"></i>
                            <span>LinkedIn</span>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('https://' . $_SERVER['HTTP_HOST'] . '/blog/' . $post->getSlug()) ?>" target="_blank" class="flex items-center text-gray-700 hover:text-blue-600 transition-colors">
                            <i class="fab fa-facebook text-lg mr-2"></i>
                            <span>Facebook</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Get and display promo content if enabled
$promoRepository = $container->get(\App\Domain\Promo\BlogPromoRepository::class);
$promo = $promoRepository->getPromo();
if ($promo->isEnabled() && !empty($promo->getContent())): 
?>
<section class="py-4">
    <div class="container mx-auto px-4 max-w-3xl">
        <?= $promo->getContent() ?>
    </div>
</section>
<?php endif; ?>

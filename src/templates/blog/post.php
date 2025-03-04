<section class="py-10 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="mb-4">
                <a href="/blog" class="inline-flex items-center text-white hover:text-gray-200 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Blog
                </a>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold mb-4"><?= htmlspecialchars($post->getTitle()) ?></h1>
            <div class="flex items-center text-sm">
                <span><?= htmlspecialchars($post->getDate()) ?></span>
                <span class="mx-2">•</span>
                <span>
                    <?php foreach ($post->getTags() as $tag): ?>
                        <span class="inline-block bg-white bg-opacity-20 rounded-full px-3 py-1 text-xs font-semibold mr-2"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </span>
            </div>
        </div>
    </div>
</section>

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="prose prose-lg mx-auto max-w-4xl px-4 py-6 bg-white rounded-lg shadow-md">
                <?= $markdownService->convertToHtml($post->getRawContent()) ?>
                
                <?php if ($post->hasYoutubeVideo()): ?>
                    <div class="mt-8 mb-4">
                        <h3 class="text-2xl font-bold text-gradient mb-4">Watch Video</h3>
                        <div class="relative w-full rounded-xl overflow-hidden" style="padding-top: 56.25%">
                            <iframe 
                                class="absolute top-0 left-0 w-full h-full shadow-lg"
                                src="<?= str_replace('watch?v=', 'embed/', htmlspecialchars($post->getYoutubeUrl())) ?>" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="max-w-4xl mx-auto mt-8 px-4 py-6 bg-white rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-gradient mb-4">Share this post</h3>
                <div class="flex space-x-6">
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode('https://' . $_SERVER['HTTP_HOST'] . '/blog/' . $post->getSlug()) ?>&text=<?= urlencode($post->getTitle()) ?>" target="_blank" class="flex items-center text-gray-700 hover:text-blue-500 transition-colors">
                        <i class="fab fa-twitter text-xl mr-2"></i>
                        <span>Twitter</span>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode('https://' . $_SERVER['HTTP_HOST'] . '/blog/' . $post->getSlug()) ?>&title=<?= urlencode($post->getTitle()) ?>" target="_blank" class="flex items-center text-gray-700 hover:text-blue-700 transition-colors">
                        <i class="fab fa-linkedin text-xl mr-2"></i>
                        <span>LinkedIn</span>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('https://' . $_SERVER['HTTP_HOST'] . '/blog/' . $post->getSlug()) ?>" target="_blank" class="flex items-center text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fab fa-facebook text-xl mr-2"></i>
                        <span>Facebook</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-12 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-4">Blog</h1>
        <p class="text-xl">Thoughts on PHP, AI, and web development</p>
    </div>
</section>

<section class="py-12">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <?php foreach ($posts as $post): ?>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <?php if ($post->hasFeaturedImage()): ?>
                <a href="/blog/<?= htmlspecialchars($post->getSlug()) ?>" class="block">
                    <div class="h-48 overflow-hidden">
                        <img src="<?= htmlspecialchars($post->getFeaturedImage()) ?>" alt="<?= htmlspecialchars($post->getTitle()) ?>" class="w-full h-full object-cover">
                    </div>
                </a>
                <?php endif; ?>
                <div class="p-6">
                    <div class="flex items-center text-gray-500 text-sm mb-2">
                        <span><?= htmlspecialchars($post->getDate()) ?></span>
                        <span class="mx-2">•</span>
                        <span>
                            <?php foreach ($post->getTags() as $tag): ?>
                                <span class="inline-block bg-gray-100 rounded-full px-3 py-1 text-xs font-semibold text-gray-600 mr-2"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </span>
                    </div>
                    <h2 class="text-2xl font-semibold mb-3">
                        <a href="/blog/<?= htmlspecialchars($post->getSlug()) ?>" class="hover:text-indigo-600 transition-colors">
                            <?= htmlspecialchars($post->getTitle()) ?>
                        </a>
                    </h2>
                    <p class="text-gray-600 mb-4"><?= htmlspecialchars($post->getExcerpt()) ?></p>
                    <a href="/blog/<?= htmlspecialchars($post->getSlug()) ?>" class="text-indigo-600 font-medium hover:text-indigo-800 transition-colors">Read more →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

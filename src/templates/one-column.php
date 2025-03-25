<section class="py-12 <?= $template_settings['header_bg_color'] ?? 'bg-gradient-to-r from-indigo-600 to-purple-600' ?> <?= $template_settings['header_text_color'] ?? 'text-white' ?>">
    <div class="container mx-auto px-4 text-center">
        <img src="<?= $profile_image ?? 'https://placehold.co/400x400/e2e8f0/a1a1aa?text=400+x+400' ?>" alt="Profile Image" class="w-32 h-32 rounded-full mx-auto mb-6">
        <h1 class="text-4xl font-bold mb-2"><?= $name ?? 'Jane Doe' ?></h1>
        <p class="text-xl"><?= $title ?? 'Frontend Developer & UI/UX Designer' ?></p>
        
        <div class="flex justify-center space-x-6 mt-6">
            <?php if (isset($social_links['github'])): ?>
                <a href="<?= htmlspecialchars($social_links['github']) ?>" class="text-white hover:text-gray-200 transition-colors" target="_blank">
                    <i class="fab fa-github text-2xl"></i>
                </a>
            <?php endif; ?>
            
            <?php if (isset($social_links['linkedin'])): ?>
                <a href="<?= htmlspecialchars($social_links['linkedin']) ?>" class="text-white hover:text-gray-200 transition-colors" target="_blank">
                    <i class="fab fa-linkedin text-2xl"></i>
                </a>
            <?php endif; ?>
            
            <?php if (isset($social_links['twitter'])): ?>
                <a href="<?= htmlspecialchars($social_links['twitter']) ?>" class="text-white hover:text-gray-200 transition-colors" target="_blank">
                    <i class="fab fa-twitter text-2xl"></i>
                </a>
            <?php endif; ?>
            
            <?php if (isset($social_links['dribbble'])): ?>
                <a href="<?= htmlspecialchars($social_links['dribbble']) ?>" class="text-white hover:text-gray-200 transition-colors" target="_blank">
                    <i class="fab fa-dribbble text-2xl"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b border-gray-200">About Me</h2>
            <div class="prose max-w-none">
                <?= $about_content ?? '<p>Hello! I\'m Jane, a passionate frontend developer and UI/UX designer with 5 years of experience creating beautiful, functional, and user-centered digital experiences. Based in San Francisco, I enjoy turning complex problems into simple, intuitive designs.</p><p>When I\'m not coding or designing, you\'ll find me hiking, reading sci-fi novels, or experimenting with new cooking recipes. I believe in continuous learning and am currently exploring WebGL and 3D web experiences.</p>' ?>
            </div>
        </div>
        
        <?php if (isset($blog_posts) && !empty($blog_posts) && ($template_settings['show_blog_posts'] ?? '1') === '1'): ?>
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b border-gray-200">Latest Blog Posts</h2>
            <div class="space-y-6">
                <?php 
                $max_blog_posts = (int)($template_settings['max_blog_posts'] ?? 5);
                $displayed_posts = array_slice($blog_posts, 0, $max_blog_posts);
                foreach ($displayed_posts as $post): 
                ?>
                <div class="bg-gray-50 p-6 rounded-lg hover:shadow-md transition-shadow">
                    <div class="flex items-center text-gray-500 text-sm mb-2">
                        <span><?= htmlspecialchars($post->getDate()) ?></span>
                        <span class="mx-2">•</span>
                        <span>
                            <?php foreach ($post->getTags() as $tag): ?>
                                <span class="inline-block bg-gray-100 rounded-full px-3 py-1 text-xs font-semibold text-gray-600 mr-2"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">
                        <a href="/blog/<?= htmlspecialchars($post->getSlug()) ?>" class="hover:text-indigo-600 transition-colors">
                            <?= htmlspecialchars($post->getTitle()) ?>
                        </a>
                    </h3>
                    <p class="text-gray-600 mb-3"><?= htmlspecialchars($post->getExcerpt()) ?></p>
                    <a href="/blog/<?= htmlspecialchars($post->getSlug()) ?>" class="text-indigo-600 font-medium hover:text-indigo-800 transition-colors">Read more →</a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-6 text-center">
                <a href="/blog" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors">View All Posts</a>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (isset($video_posts) && !empty($video_posts) && ($template_settings['show_video_posts'] ?? '1') === '1'): ?>
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b border-gray-200">Video Content</h2>
            <div class="space-y-6">
                <?php 
                $max_video_posts = (int)($template_settings['max_video_posts'] ?? 3);
                $displayed_video_posts = array_slice($video_posts, 0, $max_video_posts);
                foreach ($displayed_video_posts as $post): 
                ?>
                <div class="bg-gray-50 p-6 rounded-lg hover:shadow-md transition-shadow">
                    <?php if ($post->hasYoutubeVideo()): ?>
                    <div class="mb-4 rounded-lg overflow-hidden">
                        <div class="relative w-full" style="padding-top: 56.25%">
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
                    <h3 class="text-xl font-semibold mb-2">
                        <a href="/blog/<?= htmlspecialchars($post->getSlug()) ?>" class="hover:text-indigo-600 transition-colors">
                            <?= htmlspecialchars($post->getTitle()) ?>
                        </a>
                    </h3>
                    <p class="text-gray-600 mb-3"><?= htmlspecialchars($post->getExcerpt()) ?></p>
                    <a href="/blog/<?= htmlspecialchars($post->getSlug()) ?>" class="text-indigo-600 font-medium hover:text-indigo-800 transition-colors">Read more →</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (isset($partner_links) && !empty($partner_links) && ($template_settings['show_partner_links'] ?? '1') === '1'): ?>
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b border-gray-200">Affiliate Links</h2>
            <div class="space-y-4">
                <?php foreach ($partner_links as $link): ?>
                <div class="bg-gray-50 p-5 rounded-lg hover:shadow-md transition-shadow">
                    <a href="/<?= htmlspecialchars($link->getSlug()) ?>" class="block hover:text-indigo-600 transition-colors">
                        <h3 class="text-lg font-semibold mb-1"><?= htmlspecialchars($link->getDescription()) ?></h3>
                        <p class="text-gray-500 text-sm">www.<?= parse_url($link->getTargetUrl(), PHP_URL_HOST) ?></p>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

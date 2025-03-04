<section class="py-20 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-6"><?= $content['hero']['headline'] ?? 'PHP Developer & AI Evangelist' ?></h1>
                <p class="text-xl md:text-2xl mb-8"><?= $content['hero']['subheading'] ?? 'Building innovative web solutions that leverage the power of artificial intelligence.' ?></p>
                <div class="flex space-x-4">
                    <?php if (isset($content['hero']['cta_primary'])): ?>
                        <a href="<?= htmlspecialchars($content['hero']['cta_primary']['url']) ?>" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors"><?= htmlspecialchars($content['hero']['cta_primary']['text']) ?></a>
                    <?php endif; ?>
                    <?php if (isset($content['hero']['cta_secondary'])): ?>
                        <a href="<?= htmlspecialchars($content['hero']['cta_secondary']['url']) ?>" class="bg-transparent border-2 border-white px-6 py-3 rounded-lg font-medium hover:bg-white hover:text-indigo-600 transition-colors"><?= htmlspecialchars($content['hero']['cta_secondary']['text']) ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="md:w-1/2">
                <div class="bg-white p-6 rounded-lg shadow-xl">
                    <div class="text-gray-800">
                        <div class="flex mb-4">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        </div>
                        <div class="font-mono text-sm">
                            <p class="text-gray-600"><?= $content['code_example']['comment_1'] ?? '// The future of web development' ?></p>
                            <p><span class="text-purple-600">function</span> <span class="text-blue-600">createInnovation</span>() {</p>
                            <p class="pl-4"><span class="text-purple-600">const</span> skills = [
                                <?php if (isset($content['code_example']['skills']) && is_array($content['code_example']['skills'])): ?>
                                    <?php $skills = array_map(function($skill) { return "<span class=\"text-green-600\">'$skill'</span>"; }, $content['code_example']['skills']); ?>
                                    <?= implode(', ', $skills) ?>
                                <?php else: ?>
                                    <span class="text-green-600">'PHP'</span>, <span class="text-green-600">'AI'</span>, <span class="text-green-600">'Problem Solving'</span>
                                <?php endif; ?>
                            ];</p>
                            <p class="pl-4"><span class="text-purple-600">const</span> passion = <span class="text-orange-600"><?= $content['code_example']['passion'] ?? '100' ?></span>;</p>
                            <p class="pl-4"><span class="text-purple-600">return</span> <span class="text-green-600">'<?= htmlspecialchars($content['code_example']['tagline'] ?? 'Web solutions that make a difference') ?>'</span>;</p>
                            <p>}</p>
                            <p class="mt-2"><span class="text-blue-600">createInnovation</span>();  <span class="text-gray-600"><?= $content['code_example']['comment_2'] ?? '// Let\'s build something amazing' ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12"><?= $content['services']['title'] ?? 'What I Do' ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php if (isset($content['services']['items']) && is_array($content['services']['items'])): ?>
                <?php foreach ($content['services']['items'] as $service): ?>
                    <div class="p-6 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-indigo-600 text-4xl mb-4">
                            <i class="<?= htmlspecialchars($service['icon'] ?? 'fas fa-code') ?>"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3"><?= htmlspecialchars($service['title']) ?></h3>
                        <p class="text-gray-600"><?= htmlspecialchars($service['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-indigo-600 text-4xl mb-4">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">PHP Development</h3>
                    <p class="text-gray-600">Creating robust, scalable web applications using modern PHP frameworks and best practices.</p>
                </div>
                
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-indigo-600 text-4xl mb-4">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">AI Integration</h3>
                    <p class="text-gray-600">Implementing AI capabilities into web applications to enhance functionality and user experience.</p>
                </div>
                
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-indigo-600 text-4xl mb-4">
                        <i class="fas fa-server"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">API Development</h3>
                    <p class="text-gray-600">Building efficient, secure, and well-documented APIs that power modern applications.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12"><?= $content['blog_section']['title'] ?? 'Latest Blog Posts' ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($latestPosts as $post): ?>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
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
                    <h3 class="text-xl font-semibold mb-3">
                        <a href="/blog/<?= htmlspecialchars($post->getSlug()) ?>" class="hover:text-indigo-600 transition-colors">
                            <?= htmlspecialchars($post->getTitle()) ?>
                        </a>
                    </h3>
                    <p class="text-gray-600 mb-4"><?= htmlspecialchars($post->getExcerpt()) ?></p>
                    <a href="/blog/<?= htmlspecialchars($post->getSlug()) ?>" class="text-indigo-600 font-medium hover:text-indigo-800 transition-colors">Read more →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-10">
            <?php if (isset($content['blog_section']['cta'])): ?>
                <a href="<?= htmlspecialchars($content['blog_section']['cta']['url']) ?>" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition-colors"><?= htmlspecialchars($content['blog_section']['cta']['text']) ?></a>
            <?php else: ?>
                <a href="/blog" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition-colors">View All Posts</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center md:space-x-8">
            <!-- Left Side: Text Content -->
            <div class="w-full md:w-1/2 mb-8 md:mb-0">
                <h2 class="text-3xl font-bold mb-4"><?= $content['cta_section']['title'] ?? 'Let\'s Create Something Together' ?></h2>
                <p class="text-gray-600 mb-6"><?= $content['cta_section']['description'] ?? 'I\'m always interested in new projects and collaborations. Whether you have a specific project in mind or just want to discuss possibilities, I\'d love to hear from you.' ?></p>
                <div>
                    <?php if (isset($content['cta_section']['button'])): ?>
                        <a href="<?= htmlspecialchars($content['cta_section']['button']['url']) ?>" class="inline-block bg-indigo-600 text-white px-8 py-4 rounded-lg font-medium hover:bg-indigo-700 transition-colors"><?= htmlspecialchars($content['cta_section']['button']['text']) ?></a>
                    <?php else: ?>
                        <a href="/contact" class="inline-block bg-indigo-600 text-white px-8 py-4 rounded-lg font-medium hover:bg-indigo-700 transition-colors">Get in Touch</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Right Side: Image -->
            <div class="w-full md:w-1/2">
                <div class="rounded-lg overflow-hidden shadow-xl">
                    <?php if (isset($content['cta_section']['image']) && !empty($content['cta_section']['image'])): ?>
                        <img src="<?= htmlspecialchars($content['cta_section']['image']) ?>" alt="Collaboration" class="w-full h-auto">
                    <?php else: ?>
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" alt="Collaboration" class="w-full h-auto">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
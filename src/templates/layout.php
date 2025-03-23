<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Michal Kurecka | PHP & AI Developer' ?></title>
    
    <?php if (isset($post) && $post->hasFeaturedImage()): ?>
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="https://<?= $_SERVER['HTTP_HOST'] ?>/blog/<?= $post->getSlug() ?>">
    <meta property="og:title" content="<?= htmlspecialchars($post->getTitle()) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($post->getExcerpt()) ?>">
    <meta property="og:image" content="https://<?= $_SERVER['HTTP_HOST'] . htmlspecialchars($post->getFeaturedImage()) ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://<?= $_SERVER['HTTP_HOST'] ?>/blog/<?= $post->getSlug() ?>">
    <meta property="twitter:title" content="<?= htmlspecialchars($post->getTitle()) ?>">
    <meta property="twitter:description" content="<?= htmlspecialchars($post->getExcerpt()) ?>">
    <meta property="twitter:image" content="https://<?= $_SERVER['HTTP_HOST'] . htmlspecialchars($post->getFeaturedImage()) ?>">
    <?php endif; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {}
        }
      }
    </script>
    <style type="text/tailwindcss">
      @layer components {
        .prose {
          max-width: 65ch;
        }
        
        .text-gradient {
          background-clip: text;
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          background-image: linear-gradient(to right, #6366f1, #a855f7);
        }
      }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-6">
            <nav class="flex items-center justify-between">
                <a href="/" class="text-2xl font-bold text-indigo-600">
                    <span class="text-gradient"><?= $site_content['global']['site_name'] ?? 'Michal Kurecka' ?></span>
                </a>
                <div class="hidden md:flex space-x-8">
                    <?php if (isset($site_content['navigation']['links'])): ?>
                        <?php foreach ($site_content['navigation']['links'] as $link): ?>
                            <a href="<?= htmlspecialchars($link['url']) ?>" class="font-medium hover:text-indigo-600 transition-colors"><?= htmlspecialchars($link['text']) ?></a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a href="/" class="font-medium hover:text-indigo-600 transition-colors">Home</a>
                        <a href="/blog" class="font-medium hover:text-indigo-600 transition-colors">Blog</a>
                        <a href="/cv" class="font-medium hover:text-indigo-600 transition-colors">CV</a>
                        <a href="/contact" class="font-medium hover:text-indigo-600 transition-colors">Contact</a>
                    <?php endif; ?>
                </div>
                <div class="md:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-500 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <?php if (isset($site_content['navigation']['links'])): ?>
                            <?php foreach ($site_content['navigation']['links'] as $link): ?>
                                <a href="<?= htmlspecialchars($link['url']) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><?= htmlspecialchars($link['text']) ?></a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Home</a>
                            <a href="/blog" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Blog</a>
                            <a href="/cv" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CV</a>
                            <a href="/contact" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Contact</a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <?= $content ?>
    </main>

    <footer class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-10">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-8 md:mb-0">
                    <h3 class="text-xl font-bold mb-4"><?= $site_content['global']['site_name'] ?? 'Michal Kurecka' ?></h3>
                    <p class="text-gray-300 max-w-md"><?= $site_content['footer']['description'] ?? 'PHP Developer and AI Evangelist passionate about creating innovative solutions that combine traditional web development with cutting-edge AI technologies.' ?></p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4"><?= $site_content['footer']['connect_title'] ?? 'Connect' ?></h4>
                    <div class="flex space-x-4">
                        <?php if (isset($site_content['social_media'])): ?>
                            <?php if (isset($site_content['social_media']['github'])): ?>
                                <a href="<?= htmlspecialchars($site_content['social_media']['github']) ?>" class="text-gray-300 hover:text-white transition-colors" target="_blank">
                                    <i class="fab fa-github text-xl"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (isset($site_content['social_media']['linkedin'])): ?>
                                <a href="<?= htmlspecialchars($site_content['social_media']['linkedin']) ?>" class="text-gray-300 hover:text-white transition-colors" target="_blank">
                                    <i class="fab fa-linkedin text-xl"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (isset($site_content['social_media']['twitter'])): ?>
                                <a href="<?= htmlspecialchars($site_content['social_media']['twitter']) ?>" class="text-gray-300 hover:text-white transition-colors" target="_blank">
                                    <i class="fab fa-twitter text-xl"></i>
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="https://github.com/michalkurecka" class="text-gray-300 hover:text-white transition-colors" target="_blank">
                                <i class="fab fa-github text-xl"></i>
                            </a>
                            <a href="https://linkedin.com/in/michalkurecka" class="text-gray-300 hover:text-white transition-colors" target="_blank">
                                <i class="fab fa-linkedin text-xl"></i>
                            </a>
                            <a href="https://twitter.com/michalkurecka" class="text-gray-300 hover:text-white transition-colors" target="_blank">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-gray-400 text-sm">
                <p><?= str_replace('%YEAR%', date('Y'), $site_content['global']['copyright'] ?? '© ' . date('Y') . ' Michal Kurecka. All rights reserved.') ?></p>
            </div>
        </div>
    </footer>
</body>
</html>

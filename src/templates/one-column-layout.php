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
    <main class="container mx-auto px-4 py-8">
        <?= $content ?>
    </main>
</body>
</html>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Blog Posts</h3>
            <span class="text-indigo-600 bg-indigo-100 rounded-full w-10 h-10 flex items-center justify-center">
                <i class="fas fa-blog"></i>
            </span>
        </div>
        <p class="text-3xl font-bold"><?= count($blogPosts) ?></p>
        <p class="text-gray-500 mt-2">Total blog posts</p>
        <div class="mt-4">
            <a href="/admin/blog" class="text-indigo-600 hover:text-indigo-800 transition-colors text-sm">View all posts →</a>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Partner Links</h3>
            <span class="text-blue-600 bg-blue-100 rounded-full w-10 h-10 flex items-center justify-center">
                <i class="fas fa-share-alt"></i>
            </span>
        </div>
        <p class="text-3xl font-bold"><?= count($partnerLinks) ?></p>
        <p class="text-gray-500 mt-2">Total partner links</p>
        <div class="mt-4">
            <a href="/admin/partner" class="text-indigo-600 hover:text-indigo-800 transition-colors text-sm">Manage links →</a>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Link Clicks</h3>
            <span class="text-orange-600 bg-orange-100 rounded-full w-10 h-10 flex items-center justify-center">
                <i class="fas fa-mouse-pointer"></i>
            </span>
        </div>
        <?php
            $totalClicks = 0;
            foreach ($partnerLinks as $link) {
                $totalClicks += $link->getClickCount();
            }
        ?>
        <p class="text-3xl font-bold"><?= $totalClicks ?></p>
        <p class="text-gray-500 mt-2">Total partner link clicks</p>
        <div class="mt-4">
            <a href="/admin/partner" class="text-indigo-600 hover:text-indigo-800 transition-colors text-sm">View details →</a>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Latest Post</h3>
            <span class="text-green-600 bg-green-100 rounded-full w-10 h-10 flex items-center justify-center">
                <i class="fas fa-clock"></i>
            </span>
        </div>
        <?php if (count($blogPosts) > 0): ?>
            <?php
                usort($blogPosts, function($a, $b) {
                    return strtotime($b->getDate()) - strtotime($a->getDate());
                });
                $latestPost = $blogPosts[0];
            ?>
            <p class="font-medium"><?= htmlspecialchars($latestPost->getTitle()) ?></p>
            <p class="text-gray-500 mt-2"><?= htmlspecialchars($latestPost->getDate()) ?></p>
            <div class="mt-4">
                <a href="/admin/blog/edit/<?= $latestPost->getId() ?>" class="text-indigo-600 hover:text-indigo-800 transition-colors text-sm">Edit post →</a>
            </div>
        <?php else: ?>
            <p class="text-gray-500">No blog posts yet</p>
            <div class="mt-4">
                <a href="/admin/blog/new" class="text-indigo-600 hover:text-indigo-800 transition-colors text-sm">Create your first post →</a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>
            <span class="text-purple-600 bg-purple-100 rounded-full w-10 h-10 flex items-center justify-center">
                <i class="fas fa-bolt"></i>
            </span>
        </div>
        <div class="space-y-2">
            <a href="/admin/blog/new" class="block text-gray-700 hover:text-indigo-600 transition-colors">
                <i class="fas fa-plus-circle mr-2"></i> Add New Blog Post
            </a>
            <a href="/admin/cv" class="block text-gray-700 hover:text-indigo-600 transition-colors">
                <i class="fas fa-edit mr-2"></i> Update CV
            </a>
            <a href="/admin/content" class="block text-gray-700 hover:text-indigo-600 transition-colors">
                <i class="fas fa-file-alt mr-2"></i> Edit Site Content
            </a>
            <a href="/admin/contact" class="block text-gray-700 hover:text-indigo-600 transition-colors">
                <i class="fas fa-envelope mr-2"></i> View Contact Messages
            </a>
            <a href="/admin/webhook" class="block text-gray-700 hover:text-indigo-600 transition-colors">
                <i class="fas fa-link mr-2"></i> Webhook Settings
            </a>
            <a href="/admin/partner" class="block text-gray-700 hover:text-indigo-600 transition-colors">
                <i class="fas fa-share-alt mr-2"></i> Partner Links
            </a>
            <a href="/admin/promo" class="block text-gray-700 hover:text-indigo-600 transition-colors">
                <i class="fas fa-bullhorn mr-2"></i> Blog Promo Box
            </a>
            <a href="/admin/settings" class="block text-gray-700 hover:text-indigo-600 transition-colors">
                <i class="fas fa-cog mr-2"></i> Template Settings
            </a>
            <a href="/" target="_blank" class="block text-gray-700 hover:text-indigo-600 transition-colors">
                <i class="fas fa-external-link-alt mr-2"></i> View Website
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Recent Blog Posts</h3>
        <a href="/admin/blog/new" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus mr-1"></i> New Post
        </a>
    </div>
    
    <?php if (count($blogPosts) > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Title</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Date</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Tags</th>
                        <th class="text-right py-3 px-4 font-semibold text-sm text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        usort($blogPosts, function($a, $b) {
                            return strtotime($b->getDate()) - strtotime($a->getDate());
                        });
                        $recentPosts = array_slice($blogPosts, 0, 5);
                    ?>
                    
                    <?php foreach ($recentPosts as $post): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <a href="/blog/<?= htmlspecialchars($post->getSlug()) ?>" target="_blank" class="font-medium text-gray-800 hover:text-indigo-600 transition-colors">
                                <?= htmlspecialchars($post->getTitle()) ?>
                            </a>
                        </td>
                        <td class="py-3 px-4 text-gray-500"><?= htmlspecialchars($post->getDate()) ?></td>
                        <td class="py-3 px-4">
                            <?php foreach (array_slice($post->getTags(), 0, 2) as $tag): ?>
                                <span class="inline-block bg-gray-100 rounded-full px-2 py-1 text-xs font-semibold text-gray-600 mr-1"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                            <?php if (count($post->getTags()) > 2): ?>
                                <span class="text-xs text-gray-500">+<?= count($post->getTags()) - 2 ?> more</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="/admin/blog/edit/<?= $post->getId() ?>" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/admin/blog/delete/<?= $post->getId() ?>" class="text-red-600 hover:text-red-800 transition-colors" onclick="return confirm('Are you sure you want to delete this post?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (count($blogPosts) > 5): ?>
        <div class="mt-4 text-center">
            <a href="/admin/blog" class="text-indigo-600 hover:text-indigo-800 transition-colors">View all <?= count($blogPosts) ?> posts →</a>
        </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="text-center py-8">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-blog text-5xl"></i>
            </div>
            <h4 class="text-xl font-medium text-gray-800 mb-2">No blog posts yet</h4>
            <p class="text-gray-500 mb-4">Create your first blog post to share your thoughts and expertise.</p>
            <a href="/admin/blog/new" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                Create Your First Post
            </a>
        </div>
    <?php endif; ?>
</div>

<div class="bg-white rounded-lg shadow-sm p-6 mt-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Recent Partner Links</h3>
        <a href="/admin/partner/new" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus mr-1"></i> New Link
        </a>
    </div>
    
    <?php if (count($partnerLinks) > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Slug</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Target URL</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Description</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Clicks</th>
                        <th class="text-right py-3 px-4 font-semibold text-sm text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        usort($partnerLinks, function($a, $b) {
                            return strtotime($b->getCreatedAt()) - strtotime($a->getCreatedAt());
                        });
                        $recentLinks = array_slice($partnerLinks, 0, 5);
                    ?>
                    
                    <?php foreach ($recentLinks as $link): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <a href="/<?= htmlspecialchars($link->getSlug()) ?>" target="_blank" class="font-medium text-gray-800 hover:text-indigo-600 transition-colors mr-2">
                                    <?= htmlspecialchars($link->getSlug()) ?>
                                </a>
                                <button 
                                    class="text-gray-500 hover:text-gray-700 copy-link" 
                                    data-url="<?= htmlspecialchars($_SERVER['HTTP_HOST'] . '/' . $link->getSlug()) ?>"
                                    title="Copy link to clipboard"
                                    onclick="copyToClipboard('<?= htmlspecialchars($_SERVER['HTTP_HOST'] . '/' . $link->getSlug()) ?>')"
                                >
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <a href="<?= htmlspecialchars($link->getTargetUrl()) ?>" target="_blank" class="text-blue-600 hover:text-blue-900 truncate block max-w-xs">
                                <?= htmlspecialchars($link->getTargetUrl()) ?>
                            </a>
                        </td>
                        <td class="py-3 px-4 text-gray-500"><?= htmlspecialchars($link->getDescription()) ?></td>
                        <td class="py-3 px-4 text-gray-500"><?= htmlspecialchars($link->getClickCount()) ?></td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="/admin/partner/edit/<?= $link->getId() ?>" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/admin/partner/delete/<?= $link->getId() ?>" class="text-red-600 hover:text-red-800 transition-colors" onclick="return confirm('Are you sure you want to delete this link?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (count($partnerLinks) > 5): ?>
        <div class="mt-4 text-center">
            <a href="/admin/partner" class="text-indigo-600 hover:text-indigo-800 transition-colors">View all <?= count($partnerLinks) ?> links →</a>
        </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="text-center py-8">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-share-alt text-5xl"></i>
            </div>
            <h4 class="text-xl font-medium text-gray-800 mb-2">No partner links yet</h4>
            <p class="text-gray-500 mb-4">Create your first partner link to start tracking affiliate redirects.</p>
            <a href="/admin/partner/new" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                Create Your First Link
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
function copyToClipboard(text) {
    // Create a temporary input element
    const input = document.createElement('input');
    input.setAttribute('value', text);
    document.body.appendChild(input);
    
    // Select the text
    input.select();
    input.setSelectionRange(0, 99999); // For mobile devices
    
    // Copy the text to clipboard
    document.execCommand('copy');
    
    // Remove the temporary element
    document.body.removeChild(input);
    
    // Show a notification or change the button appearance temporarily
    alert('Link copied to clipboard: ' + text);
}
</script>

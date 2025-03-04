<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800">All Blog Posts</h2>
    <a href="/admin/blog/new" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition-colors">
        <i class="fas fa-plus mr-1"></i> New Post
    </a>
</div>

<?php if (count($posts) > 0): ?>
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Title</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Date</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Tags</th>
                        <th class="text-right py-3 px-4 font-semibold text-sm text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <a href="/blog/<?= htmlspecialchars($post->getSlug()) ?>" target="_blank" class="font-medium text-gray-800 hover:text-indigo-600 transition-colors">
                                <?= htmlspecialchars($post->getTitle()) ?>
                            </a>
                        </td>
                        <td class="py-3 px-4 text-gray-500"><?= htmlspecialchars($post->getDate()) ?></td>
                        <td class="py-3 px-4">
                            <?php foreach ($post->getTags() as $tag): ?>
                                <span class="inline-block bg-gray-100 rounded-full px-2 py-1 text-xs font-semibold text-gray-600 mr-1 mb-1"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
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
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
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
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6"><?= isset($post) ? 'Edit' : 'New' ?> Blog Post</h2>
    
    <form action="<?= isset($post) ? '/admin/blog/update/' . $post->getId() : '/admin/blog/create' ?>" method="post" enctype="multipart/form-data" class="space-y-6">
        <div>
            <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
            <input type="text" id="title" name="title" value="<?= isset($post) ? htmlspecialchars($post->getTitle()) : '' ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
        </div>
        
        <div>
            <label for="slug" class="block text-gray-700 font-medium mb-2">Slug</label>
            <input type="text" id="slug" name="slug" value="<?= isset($post) ? htmlspecialchars($post->getSlug()) : '' ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
            <p class="text-gray-500 text-sm mt-1">URL-friendly version of the title (e.g., "my-blog-post")</p>
        </div>
        
        <div>
            <label for="date" class="block text-gray-700 font-medium mb-2">Date</label>
            <input type="date" id="date" name="date" value="<?= isset($post) ? htmlspecialchars($post->getDate()) : date('Y-m-d') ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
        </div>
        
        <div>
            <label for="excerpt" class="block text-gray-700 font-medium mb-2">Excerpt</label>
            <textarea id="excerpt" name="excerpt" rows="2" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"><?= isset($post) ? htmlspecialchars($post->getExcerpt()) : '' ?></textarea>
            <p class="text-gray-500 text-sm mt-1">A brief summary of the post to display in listings</p>
        </div>
        
        <div>
            <label for="content" class="block text-gray-700 font-medium mb-2">Content</label>
            <textarea id="content" name="content" rows="15" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"><?= isset($post) ? htmlspecialchars($post->getContent()) : '' ?></textarea>
            <p class="text-gray-500 text-sm mt-1">Markdown formatting is supported</p>
        </div>
        
        <div>
            <label for="tags" class="block text-gray-700 font-medium mb-2">Tags</label>
            <input type="text" id="tags" name="tags" value="<?= isset($post) ? htmlspecialchars(implode(', ', $post->getTags())) : '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
            <p class="text-gray-500 text-sm mt-1">Comma-separated list of tags (e.g., "PHP, AI, Development")</p>
        </div>
        
        <div>
            <label for="youtube_url" class="block text-gray-700 font-medium mb-2">YouTube Video URL</label>
            <input type="text" id="youtube_url" name="youtube_url" value="<?= isset($post) ? htmlspecialchars($post->getYoutubeUrl() ?? '') : '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
            <p class="text-gray-500 text-sm mt-1">Full YouTube video URL (optional)</p>
        </div>
        
        <div>
            <label class="block text-gray-700 font-medium mb-2">Featured Image</label>
            
            <?php if (isset($post) && $post->hasFeaturedImage()): ?>
                <div class="mb-4">
                    <div class="border border-gray-300 rounded-lg p-2 mb-2">
                        <img src="<?= htmlspecialchars($post->getFeaturedImage()) ?>" alt="Featured image" class="max-h-48 mx-auto">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="remove_featured_image" name="remove_featured_image" class="mr-2">
                        <label for="remove_featured_image" class="text-gray-700">Remove current image</label>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="mb-4">
                <label for="featured_image" class="block text-gray-700 mb-2">Upload New Image</label>
                <input type="file" id="featured_image" name="featured_image" accept="image/*" class="w-full">
                <p class="text-gray-500 text-sm mt-1">Recommended size: 1200×630 pixels</p>
            </div>
            
            <div>
                <label for="featured_image_url" class="block text-gray-700 mb-2">Or Enter Image URL</label>
                <input type="text" id="featured_image_url" name="featured_image_url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                <p class="text-gray-500 text-sm mt-1">External image URL (will be downloaded and saved locally)</p>
            </div>
        </div>
        
        <div class="flex justify-between">
            <a href="/admin/blog" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">Cancel</a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                <?= isset($post) ? 'Update' : 'Create' ?> Post
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('title').addEventListener('input', function() {
    if (!document.getElementById('slug').value || document.getElementById('slug') === document.activeElement) {
        // Only auto-generate slug if slug field is empty or if user is currently editing it
        let slug = this.value.toLowerCase()
            .replace(/[^\w\s-]/g, '') // Remove special characters
            .replace(/\s+/g, '-') // Replace spaces with dashes
            .replace(/-+/g, '-'); // Replace multiple dashes with single dash
        document.getElementById('slug').value = slug;
    }
});
</script>

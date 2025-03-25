<div class="bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6">Edit One-Column Template</h1>
    
    <?php if (isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <p>One-column template updated successfully!</p>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <p><?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/admin/content/one-column">
        <div class="mb-6">
            <label for="header_bg_color" class="block text-gray-700 text-sm font-bold mb-2">Header Background Color</label>
            <input type="text" id="header_bg_color" name="content[header_bg_color]" value="<?= htmlspecialchars($content['header_bg_color'] ?? 'bg-gradient-to-r from-indigo-600 to-purple-600') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-gray-500 text-xs mt-1">Enter a Tailwind CSS background class (e.g., bg-gradient-to-r from-indigo-600 to-purple-600)</p>
        </div>
        
        <div class="mb-6">
            <label for="header_text_color" class="block text-gray-700 text-sm font-bold mb-2">Header Text Color</label>
            <input type="text" id="header_text_color" name="content[header_text_color]" value="<?= htmlspecialchars($content['header_text_color'] ?? 'text-white') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-gray-500 text-xs mt-1">Enter a Tailwind CSS text color class (e.g., text-white)</p>
        </div>
        
        <div class="mb-6">
            <label for="show_blog_posts" class="block text-gray-700 text-sm font-bold mb-2">Show Blog Posts Section</label>
            <select id="show_blog_posts" name="content[show_blog_posts]" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="1" <?= ($content['show_blog_posts'] ?? '1') == '1' ? 'selected' : '' ?>>Yes</option>
                <option value="0" <?= ($content['show_blog_posts'] ?? '1') == '0' ? 'selected' : '' ?>>No</option>
            </select>
        </div>
        
        <div class="mb-6">
            <label for="show_video_posts" class="block text-gray-700 text-sm font-bold mb-2">Show Video Posts Section</label>
            <select id="show_video_posts" name="content[show_video_posts]" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="1" <?= ($content['show_video_posts'] ?? '1') == '1' ? 'selected' : '' ?>>Yes</option>
                <option value="0" <?= ($content['show_video_posts'] ?? '1') == '0' ? 'selected' : '' ?>>No</option>
            </select>
        </div>
        
        <div class="mb-6">
            <label for="show_partner_links" class="block text-gray-700 text-sm font-bold mb-2">Show Partner Links Section</label>
            <select id="show_partner_links" name="content[show_partner_links]" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="1" <?= ($content['show_partner_links'] ?? '1') == '1' ? 'selected' : '' ?>>Yes</option>
                <option value="0" <?= ($content['show_partner_links'] ?? '1') == '0' ? 'selected' : '' ?>>No</option>
            </select>
        </div>
        
        <div class="mb-6">
            <label for="max_blog_posts" class="block text-gray-700 text-sm font-bold mb-2">Maximum Blog Posts to Display</label>
            <input type="number" id="max_blog_posts" name="content[max_blog_posts]" value="<?= htmlspecialchars($content['max_blog_posts'] ?? '5') ?>" min="1" max="20" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        
        <div class="mb-6">
            <label for="max_video_posts" class="block text-gray-700 text-sm font-bold mb-2">Maximum Video Posts to Display</label>
            <input type="number" id="max_video_posts" name="content[max_video_posts]" value="<?= htmlspecialchars($content['max_video_posts'] ?? '3') ?>" min="1" max="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Save Changes
            </button>
        </div>
    </form>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6">Template Settings</h1>
    
    <?php if (isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <p><?= htmlspecialchars($success) ?></p>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/admin/settings/update">
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4">Site Template</h2>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Default Site Template</label>
                <div class="mt-2">
                    <div class="flex items-center mb-2">
                        <input type="radio" id="site_default" name="templates[site]" value="default" <?= ($templates['site'] ?? 'default') === 'default' ? 'checked' : '' ?> class="mr-2">
                        <label for="site_default" class="text-gray-700">Default Template</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="site_one_column" name="templates[site]" value="one-column" <?= ($templates['site'] ?? 'default') === 'one-column' ? 'checked' : '' ?> class="mr-2">
                        <label for="site_one_column" class="text-gray-700">One Column Template (No Menu, No Footer)</label>
                    </div>
                </div>
                <p class="text-gray-500 text-xs mt-1">This setting determines which template is used for the entire website.</p>
            </div>
        </div>
        
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4">Blog Templates</h2>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Default Blog Post Template</label>
                <div class="mt-2">
                    <div class="flex items-center mb-2">
                        <input type="radio" id="blog_default" name="templates[blog]" value="default" <?= ($templates['blog'] ?? 'default') === 'default' ? 'checked' : '' ?> class="mr-2">
                        <label for="blog_default" class="text-gray-700">Default Template</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="blog_simple" name="templates[blog]" value="simple" <?= ($templates['blog'] ?? 'default') === 'simple' ? 'checked' : '' ?> class="mr-2">
                        <label for="blog_simple" class="text-gray-700">Simple Template</label>
                    </div>
                </div>
                <p class="text-gray-500 text-xs mt-1">This setting determines which template is used for blog posts by default. Users can still override this by adding ?template=simple to the URL.</p>
            </div>
        </div>
        
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Save Settings
            </button>
        </div>
    </form>
</div>

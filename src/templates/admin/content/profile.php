<div class="bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Profile Content</h1>
    
    <?php if (isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <p>Profile content updated successfully!</p>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/admin/content/profile">
        <div class="mb-6">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" id="name" name="content[name]" value="<?= htmlspecialchars($content['name'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        
        <div class="mb-6">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Professional Title</label>
            <input type="text" id="title" name="content[title]" value="<?= htmlspecialchars($content['title'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        
        <div class="mb-6">
            <label for="profile_image" class="block text-gray-700 text-sm font-bold mb-2">Profile Image URL</label>
            <input type="text" id="profile_image" name="content[profile_image]" value="<?= htmlspecialchars($content['profile_image'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-gray-500 text-xs mt-1">Enter a URL to your profile image (400x400 recommended)</p>
        </div>
        
        <div class="mb-6">
            <label for="about_content" class="block text-gray-700 text-sm font-bold mb-2">About Me Content</label>
            <textarea id="about_content" name="content[about_content]" rows="6" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= htmlspecialchars($content['about_content'] ?? '') ?></textarea>
            <p class="text-gray-500 text-xs mt-1">HTML is supported</p>
        </div>
        
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Save Changes
            </button>
        </div>
    </form>
</div>

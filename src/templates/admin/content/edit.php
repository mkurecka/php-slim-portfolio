<h1 class="text-2xl font-bold mb-6">Edit <?= ucfirst(htmlspecialchars($section)) ?> Content</h1>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-4">
        <a href="/admin/content" class="text-indigo-600 hover:text-indigo-800 transition-colors flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Sections
        </a>
    </div>
    
    <?php if (isset($success)): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p><?= htmlspecialchars($success) ?></p>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p><?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>
    
    <form action="/admin/content/<?= htmlspecialchars($section) ?>" method="post">
        <div class="mb-4">
            <p class="text-gray-700 mb-2">Edit the JSON content directly. Be careful to maintain the proper JSON structure.</p>
            <textarea name="content" id="content" rows="20" class="w-full border border-gray-300 rounded-lg p-3 font-mono text-sm"><?= htmlspecialchars(json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?></textarea>
        </div>
        
        <div class="flex justify-between">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                Save Changes
            </button>
            <a href="/admin/content" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Optional: Add a validation or JSON formatting function here
    // This would enhance the editor experience
    
    // Example: Add a warning before leaving with unsaved changes
    let originalContent = document.getElementById('content').value;
    
    window.addEventListener('beforeunload', function(e) {
        let currentContent = document.getElementById('content').value;
        if (originalContent !== currentContent) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
});
</script>
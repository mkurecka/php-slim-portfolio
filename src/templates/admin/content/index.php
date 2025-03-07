<h1 class="text-2xl font-bold mb-6">Site Content</h1>

<div class="bg-white rounded-lg shadow-md p-6">
    <p class="mb-4">Edit the content that appears on your website. Select a section below to edit:</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
        <?php foreach ($sections as $section): ?>
            <a href="/admin/content/<?= htmlspecialchars($section) ?>" 
               class="block p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-indigo-50 hover:border-indigo-300 transition-colors">
                <h3 class="font-medium text-lg capitalize"><?= htmlspecialchars($section) ?></h3>
                <p class="text-gray-600 text-sm mt-1">Edit <?= htmlspecialchars($section) ?> content</p>
            </a>
        <?php endforeach; ?>
    </div>
</div>
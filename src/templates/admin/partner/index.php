<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Partner Links</h1>
        <a href="/admin/partner/new" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Add New Link
        </a>
    </div>

    <?php if (empty($links)): ?>
        <div class="bg-gray-100 p-6 rounded-lg text-center">
            <p class="text-gray-600">No partner links found. Create your first one!</p>
        </div>
    <?php else: ?>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target URL</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clicks</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($links as $link): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($link->getId()) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <a href="/<?= htmlspecialchars($link->getSlug()) ?>" target="_blank" class="text-blue-600 hover:text-blue-900 mr-2">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="<?= htmlspecialchars($link->getTargetUrl()) ?>" target="_blank" class="text-blue-600 hover:text-blue-900 truncate block max-w-xs">
                                    <?= htmlspecialchars($link->getTargetUrl()) ?>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($link->getDescription()) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($link->getClickCount()) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($link->getCreatedAt()) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/admin/partner/edit/<?= $link->getId() ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <a href="/admin/partner/delete/<?= $link->getId() ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this link?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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

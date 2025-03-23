<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold"><?= isset($link) ? 'Edit Partner Link' : 'New Partner Link' ?></h1>
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form method="POST" action="<?= isset($link) ? '/admin/partner/update/' . $link->getId() : '/admin/partner/create' ?>">
            <div class="mb-4">
                <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">Slug *</label>
                <input 
                    type="text" 
                    id="slug" 
                    name="slug" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="<?= isset($link) ? htmlspecialchars($link->getSlug()) : (isset($formData['slug']) ? htmlspecialchars($formData['slug']) : '') ?>"
                    required
                >
                <p class="text-gray-500 text-xs mt-1">This will be used in the URL: domain.com/<span class="font-semibold">slug</span></p>
            </div>

            <div class="mb-4">
                <label for="targetUrl" class="block text-gray-700 text-sm font-bold mb-2">Target URL *</label>
                <input 
                    type="url" 
                    id="targetUrl" 
                    name="targetUrl" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="<?= isset($link) ? htmlspecialchars($link->getTargetUrl()) : (isset($formData['targetUrl']) ? htmlspecialchars($formData['targetUrl']) : '') ?>"
                    required
                >
                <p class="text-gray-500 text-xs mt-1">The destination URL where users will be redirected</p>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    rows="3"
                ><?= isset($link) ? htmlspecialchars($link->getDescription()) : (isset($formData['description']) ? htmlspecialchars($formData['description']) : '') ?></textarea>
                <p class="text-gray-500 text-xs mt-1">Optional description for your reference</p>
            </div>

            <?php if (isset($link)): ?>
                <div class="mb-6">
                    <p class="text-gray-700 text-sm">
                        <span class="font-bold">Created:</span> <?= htmlspecialchars($link->getCreatedAt()) ?>
                    </p>
                    <p class="text-gray-700 text-sm">
                        <span class="font-bold">Click Count:</span> <?= htmlspecialchars($link->getClickCount()) ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="flex items-center justify-between">
                <button 
                    type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >
                    <?= isset($link) ? 'Update Link' : 'Create Link' ?>
                </button>
                <a 
                    href="/admin/partner" 
                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

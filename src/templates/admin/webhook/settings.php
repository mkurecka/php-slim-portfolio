<h1 class="text-2xl font-bold mb-6">Webhook Settings</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Contact Webhook Settings Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6">
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
            
            <?php if (isset($_GET['cleared'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>Webhook logs have been cleared.</p>
                </div>
            <?php endif; ?>
            
            <form action="/admin/webhook" method="post" class="space-y-4">
                <!-- Contact Webhook Settings -->
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Contact Form Webhook</h2>
                    <p class="text-gray-600 mb-6">Set up a webhook to send contact form submissions as JSON in real-time.</p>
                    
                    <div>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="contact_enabled" <?= $webhook['enabled'] ? 'checked' : '' ?> class="form-checkbox h-5 w-5 text-indigo-600">
                            <span class="text-gray-700 font-medium">Enable Contact Webhook</span>
                        </label>
                    </div>
                    
                    <div class="mt-4">
                        <label for="contact_url" class="block text-gray-700 font-medium mb-2">Webhook URL</label>
                        <input type="url" id="contact_url" name="contact_url" value="<?= htmlspecialchars($webhook['url']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="https://example.com/webhook">
                        <p class="text-sm text-gray-500 mt-1">The URL that will receive the POST request with JSON data.</p>
                    </div>
                    
                    <div class="mt-4">
                        <label for="contact_secret" class="block text-gray-700 font-medium mb-2">Webhook Secret (Optional)</label>
                        <input type="text" id="contact_secret" name="contact_secret" value="<?= htmlspecialchars($webhook['secret']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="your-secret-key">
                        <p class="text-sm text-gray-500 mt-1">Used to sign the payload for verification.</p>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">Contact Payload Format</h3>
                        <div class="bg-gray-50 p-4 rounded border border-gray-200 font-mono text-sm">
<pre>{
  "timestamp": 1646512345,
  "data": {
    "id": "12345abc",
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "Subject line",
    "message": "Full message text...",
    "date": "2023-03-04 15:30:45",
    "ip": "127.0.0.1"
  },
  "signature": "sha256-hash-if-secret-provided"
}</pre>
                        </div>
                    </div>
                </div>
                
                <!-- Blog Webhook Settings -->
                <div>
                    <h2 class="text-xl font-bold mb-4">Blog Post Webhook</h2>
                    <p class="text-gray-600 mb-6">Configure an endpoint to receive blog posts from external sources.</p>
                    
                    <div>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="blog_enabled" <?= isset($blog_webhook['enabled']) && $blog_webhook['enabled'] ? 'checked' : '' ?> class="form-checkbox h-5 w-5 text-indigo-600">
                            <span class="text-gray-700 font-medium">Enable Blog Webhook</span>
                        </label>
                    </div>
                    
                    <div class="mt-4">
                        <label for="blog_api_key" class="block text-gray-700 font-medium mb-2">API Key</label>
                        <div class="flex">
                            <input type="text" id="blog_api_key" name="blog_api_key" value="<?= htmlspecialchars($blog_webhook['api_key'] ?? '') ?>" class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:border-indigo-500" placeholder="API key for authentication" readonly>
                            <button type="button" onclick="copyApiKey()" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-r-lg hover:bg-gray-300 transition-colors">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">This key is required to authenticate webhook requests.</p>
                    </div>
                    
                    <div class="mt-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="generate_api_key" class="form-checkbox h-5 w-5 text-indigo-600">
                            <span class="text-gray-700 font-medium">Generate New API Key</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">Check this to generate a new API key (invalidates the current key).</p>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2">Webhook Endpoint</h3>
                        <div class="bg-gray-50 p-4 rounded border border-gray-200 font-mono text-sm">
                            <code>POST <?= htmlspecialchars($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']) ?>/api/webhook/blog</code>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">Blog Post Payload Format</h3>
                        <div class="bg-gray-50 p-4 rounded border border-gray-200 font-mono text-sm">
<pre>{
  "title": "Blog Post Title",
  "content": "Full markdown content...",
  "excerpt": "Optional excerpt text",
  "slug": "optional-custom-slug",
  "date": "2025-03-22",
  "tags": ["tag1", "tag2"],
  "youtube_url": "https://youtube.com/watch?v=..."
}</pre>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Only <code>title</code> and <code>content</code> are required. Other fields are optional.</p>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">Authentication</h3>
                        <p class="text-sm text-gray-600 mb-2">Include the API key in one of the following ways:</p>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            <li>Bearer token in Authorization header: <code>Authorization: Bearer YOUR_API_KEY</code></li>
                            <li>Custom header: <code>X-API-Key: YOUR_API_KEY</code></li>
                            <li>Query parameter: <code>?api_key=YOUR_API_KEY</code></li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors">Save All Settings</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Webhook Logs -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Webhook Logs</h2>
                <a href="/admin/webhook/clear-logs" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Are you sure you want to clear all logs?')">
                    <i class="fas fa-trash-alt mr-1"></i> Clear Logs
                </a>
            </div>
            
            <?php if (empty($logs)): ?>
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-history text-5xl"></i>
                    </div>
                    <h4 class="text-xl font-medium text-gray-800 mb-2">No webhook logs yet</h4>
                    <p class="text-gray-500">Logs will appear here once webhooks are sent or received.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Time</th>
                                <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Type</th>
                                <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">ID</th>
                                <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Status</th>
                                <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Response</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <?= htmlspecialchars($log['timestamp']) ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if ($log['type'] === 'blog'): ?>
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                            Blog
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">
                                            Contact
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 font-mono text-sm">
                                    <?= htmlspecialchars($log['id']) ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if ($log['status_code'] >= 200 && $log['status_code'] < 300): ?>
                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                            Success (<?= $log['status_code'] ?>)
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                            Failed (<?= $log['status_code'] ?>)
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 font-mono text-xs text-gray-600 truncate max-w-xs">
                                    <?= htmlspecialchars(substr($log['response'], 0, 50)) ?>
                                    <?php if (strlen($log['response']) > 50): ?>
                                        <span class="text-gray-400">...</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

<script>
function copyApiKey() {
    const apiKeyInput = document.getElementById('blog_api_key');
    apiKeyInput.select();
    document.execCommand('copy');
    
    // Show a temporary "Copied!" tooltip
    const button = apiKeyInput.nextElementSibling;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i> Copied!';
    setTimeout(() => {
        button.innerHTML = originalText;
    }, 2000);
}
</script>

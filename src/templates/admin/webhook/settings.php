<h1 class="text-2xl font-bold mb-6">Webhook Settings</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Settings Form -->
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
            
            <h2 class="text-xl font-bold mb-4">Configure Webhook</h2>
            <p class="text-gray-600 mb-6">Set up a webhook to receive contact form submissions as JSON in real-time.</p>
            
            <form action="/admin/webhook" method="post" class="space-y-4">
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="enabled" <?= $webhook['enabled'] ? 'checked' : '' ?> class="form-checkbox h-5 w-5 text-indigo-600">
                        <span class="text-gray-700 font-medium">Enable Webhook</span>
                    </label>
                </div>
                
                <div>
                    <label for="url" class="block text-gray-700 font-medium mb-2">Webhook URL</label>
                    <input type="url" id="url" name="url" value="<?= htmlspecialchars($webhook['url']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="https://example.com/webhook">
                    <p class="text-sm text-gray-500 mt-1">The URL that will receive the POST request with JSON data.</p>
                </div>
                
                <div>
                    <label for="secret" class="block text-gray-700 font-medium mb-2">Webhook Secret (Optional)</label>
                    <input type="text" id="secret" name="secret" value="<?= htmlspecialchars($webhook['secret']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="your-secret-key">
                    <p class="text-sm text-gray-500 mt-1">Used to sign the payload for verification.</p>
                </div>
                
                <div>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors">Save Settings</button>
                </div>
            </form>
            
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-2">Payload Format</h3>
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
                    <p class="text-gray-500">Logs will appear here once webhooks are sent.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Time</th>
                                <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Submission ID</th>
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
                                <td class="py-3 px-4 font-mono text-sm">
                                    <?= htmlspecialchars($log['submission_id']) ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if ($log['http_code'] >= 200 && $log['http_code'] < 300): ?>
                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                            Success (<?= $log['http_code'] ?>)
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                            Failed (<?= $log['http_code'] ?>)
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
</div>
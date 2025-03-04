<h1 class="text-2xl font-bold mb-6">Contact Form Submissions</h1>

<div class="bg-white rounded-lg shadow-md p-6">
    <?php if (isset($_GET['deleted'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>Submission deleted successfully.</p>
        </div>
    <?php endif; ?>
    
    <?php if (empty($submissions)): ?>
        <div class="text-center py-8">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-envelope text-5xl"></i>
            </div>
            <h4 class="text-xl font-medium text-gray-800 mb-2">No contact submissions yet</h4>
            <p class="text-gray-500">When visitors submit the contact form, their messages will appear here.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Date</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Name</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Email</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">Subject</th>
                        <th class="text-right py-3 px-4 font-semibold text-sm text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $submission): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-500">
                            <?= htmlspecialchars($submission->getDate()) ?>
                            <div class="text-xs text-gray-400"><?= htmlspecialchars($submission->getIp()) ?></div>
                        </td>
                        <td class="py-3 px-4 font-medium">
                            <?= htmlspecialchars($submission->getName()) ?>
                        </td>
                        <td class="py-3 px-4">
                            <a href="mailto:<?= htmlspecialchars($submission->getEmail()) ?>" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <?= htmlspecialchars($submission->getEmail()) ?>
                            </a>
                        </td>
                        <td class="py-3 px-4">
                            <?= htmlspecialchars($submission->getSubject()) ?>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <button type="button" 
                                class="text-blue-600 hover:text-blue-800 transition-colors"
                                onclick="showMessage('<?= htmlspecialchars(addslashes($submission->getId())) ?>', '<?= htmlspecialchars(addslashes($submission->getName())) ?>', '<?= htmlspecialchars(addslashes($submission->getEmail())) ?>', '<?= htmlspecialchars(addslashes($submission->getSubject())) ?>', '<?= htmlspecialchars(addslashes($submission->getMessage())) ?>')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="/admin/contact/delete/<?= $submission->getId() ?>" class="text-red-600 hover:text-red-800 transition-colors" onclick="return confirm('Are you sure you want to delete this submission?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Message Modal -->
<div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-2xl w-full mx-4 shadow-xl">
        <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-semibold" id="modalTitle">Message from Name</h3>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <div class="text-gray-600 text-sm">From:</div>
                <div id="modalFrom" class="font-medium"></div>
            </div>
            <div class="mb-4">
                <div class="text-gray-600 text-sm">Subject:</div>
                <div id="modalSubject" class="font-medium"></div>
            </div>
            <div>
                <div class="text-gray-600 text-sm">Message:</div>
                <div id="modalMessage" class="mt-2 bg-gray-50 p-4 rounded border border-gray-200 whitespace-pre-wrap"></div>
            </div>
        </div>
        <div class="border-t border-gray-200 px-6 py-4 text-right">
            <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded font-medium mr-2" onclick="closeModal()">
                Close
            </button>
            <a id="modalEmailLink" href="mailto:" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded font-medium">
                Reply by Email
            </a>
        </div>
    </div>
</div>

<script>
    function showMessage(id, name, email, subject, message) {
        document.getElementById('modalTitle').innerText = 'Message from ' + name;
        document.getElementById('modalFrom').innerText = name + ' <' + email + '>';
        document.getElementById('modalSubject').innerText = subject;
        document.getElementById('modalMessage').innerText = message;
        document.getElementById('modalEmailLink').href = 'mailto:' + email + '?subject=Re: ' + subject;
        
        document.getElementById('messageModal').classList.remove('hidden');
        document.getElementById('messageModal').classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeModal() {
        document.getElementById('messageModal').classList.add('hidden');
        document.getElementById('messageModal').classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }
</script>
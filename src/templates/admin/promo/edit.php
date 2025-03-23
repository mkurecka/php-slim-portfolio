<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Blog Promo Box</h3>
    </div>
    
    <form action="/admin/promo/update" method="post">
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <input type="checkbox" id="enabled" name="enabled" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" <?= $promo->isEnabled() ? 'checked' : '' ?>>
                <label for="enabled" class="ml-2 block text-sm text-gray-900">Enable promo box</label>
            </div>
            <p class="text-sm text-gray-500 mb-4">When enabled, this HTML content will be displayed between the title and content of each blog post.</p>
            
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">HTML Content</label>
            <textarea id="content" name="content" rows="10" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter HTML content for the promo box"><?= htmlspecialchars($promo->getContent()) ?></textarea>
            <p class="mt-2 text-sm text-gray-500">You can use HTML tags to format your promo content.</p>
        </div>
        
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                Save Changes
            </button>
            <a href="/admin/dashboard" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
            </a>
        </div>
    </form>
    
    <div class="mt-8">
        <h4 class="text-md font-semibold text-gray-800 mb-4">Preview</h4>
        <div class="border border-gray-300 rounded-md p-4 bg-gray-50">
            <div class="prose prose-sm max-w-none">
                <?= $promo->getContent() ?>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">HTML Tips</h3>
    
    <div class="prose prose-sm max-w-none">
        <p>Here are some examples of HTML you can use in your promo box:</p>
        
        <h4>Basic formatting</h4>
        <pre class="bg-gray-100 p-2 rounded"><code>&lt;p&gt;This is a paragraph.&lt;/p&gt;
&lt;strong&gt;Bold text&lt;/strong&gt;
&lt;em&gt;Italic text&lt;/em&gt;
&lt;a href="https://example.com"&gt;Link text&lt;/a&gt;</code></pre>
        
        <h4>Styled box</h4>
        <pre class="bg-gray-100 p-2 rounded"><code>&lt;div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert"&gt;
  &lt;p class="font-bold"&gt;Special Offer!&lt;/p&gt;
  &lt;p&gt;Check out my new course on web development.&lt;/p&gt;
  &lt;a href="#" class="font-bold text-blue-600 hover:underline"&gt;Learn more →&lt;/a&gt;
&lt;/div&gt;</code></pre>
        
        <h4>Button</h4>
        <pre class="bg-gray-100 p-2 rounded"><code>&lt;a href="https://example.com" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors"&gt;
  Click Here
&lt;/a&gt;</code></pre>
    </div>
</div>

<section class="py-12 bg-gradient-to-r from-indigo-600 to-blue-600 text-white">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-4"><?= $content['hero']['title'] ?? 'Get in Touch' ?></h1>
        <p class="text-xl"><?= $content['hero']['subtitle'] ?? 'Let\'s discuss how we can work together' ?></p>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="flex flex-col md:flex-row">
            <div class="w-full md:w-1/2 mb-8 md:mb-0 md:pr-8">
                <h2 class="text-2xl font-bold mb-6 text-gray-800"><?= $content['contact_info']['title'] ?? 'Contact Information' ?></h2>
                <p class="text-gray-600 mb-8"><?= $content['contact_info']['description'] ?? 'Feel free to reach out to me through any of the following channels or by using the contact form. I\'m always open to discussing new projects, creative ideas, or opportunities to be part of your vision.' ?></p>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <i class="fas fa-envelope text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800"><?= $content['contact_info']['sections']['email']['title'] ?? 'Email' ?></h3>
                            <a href="mailto:<?= htmlspecialchars($contact_info['email'] ?? 'contact@example.com') ?>" class="text-indigo-600 hover:text-indigo-800 transition-colors"><?= htmlspecialchars($contact_info['email'] ?? 'contact@example.com') ?></a>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <i class="fas fa-map-marker-alt text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800"><?= $content['contact_info']['sections']['location']['title'] ?? 'Location' ?></h3>
                            <p class="text-gray-600"><?= htmlspecialchars($contact_info['location'] ?? 'Prague, Czech Republic') ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <i class="fas fa-globe text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800"><?= $content['contact_info']['sections']['social']['title'] ?? 'Social Media' ?></h3>
                            <div class="flex space-x-3 mt-2">
                                <?php if (isset($site_content['social_media'])): ?>
                                    <?php if (isset($site_content['social_media']['linkedin'])): ?>
                                        <a href="<?= htmlspecialchars($site_content['social_media']['linkedin']) ?>" target="_blank" class="text-gray-500 hover:text-indigo-600 transition-colors">
                                            <i class="fab fa-linkedin text-xl"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($site_content['social_media']['twitter'])): ?>
                                        <a href="<?= htmlspecialchars($site_content['social_media']['twitter']) ?>" target="_blank" class="text-gray-500 hover:text-indigo-600 transition-colors">
                                            <i class="fab fa-twitter text-xl"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($site_content['social_media']['github'])): ?>
                                        <a href="<?= htmlspecialchars($site_content['social_media']['github']) ?>" target="_blank" class="text-gray-500 hover:text-indigo-600 transition-colors">
                                            <i class="fab fa-github text-xl"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="https://linkedin.com/in/michalkurecka" target="_blank" class="text-gray-500 hover:text-indigo-600 transition-colors">
                                        <i class="fab fa-linkedin text-xl"></i>
                                    </a>
                                    <a href="https://twitter.com/michalkurecka" target="_blank" class="text-gray-500 hover:text-indigo-600 transition-colors">
                                        <i class="fab fa-twitter text-xl"></i>
                                    </a>
                                    <a href="https://github.com/michalkurecka" target="_blank" class="text-gray-500 hover:text-indigo-600 transition-colors">
                                        <i class="fab fa-github text-xl"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="w-full md:w-1/2">
                <h2 class="text-2xl font-bold mb-6 text-gray-800"><?= $content['form']['title'] ?? 'Send a Message' ?></h2>
                
                <?php if (isset($success)): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p><?= $success ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p><?= $error ?></p>
                    </div>
                <?php endif; ?>
                
                <form action="/contact" method="post" class="space-y-4">
                    <div>
                        <label for="name" class="block text-gray-700 font-medium mb-2"><?= $content['form']['fields']['name']['label'] ?? 'Name' ?></label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="<?= htmlspecialchars($content['form']['fields']['name']['placeholder'] ?? 'Your name') ?>">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2"><?= $content['form']['fields']['email']['label'] ?? 'Email' ?></label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="<?= htmlspecialchars($content['form']['fields']['email']['placeholder'] ?? 'Your email address') ?>">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-gray-700 font-medium mb-2"><?= $content['form']['fields']['subject']['label'] ?? 'Subject' ?></label>
                        <input type="text" id="subject" name="subject" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="<?= htmlspecialchars($content['form']['fields']['subject']['placeholder'] ?? 'Subject of your message') ?>">
                    </div>
                    
                    <div>
                        <label for="message" class="block text-gray-700 font-medium mb-2"><?= $content['form']['fields']['message']['label'] ?? 'Message' ?></label>
                        <textarea id="message" name="message" rows="5" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="<?= htmlspecialchars($content['form']['fields']['message']['placeholder'] ?? 'Your message') ?>"></textarea>
                    </div>
                    
                    <div>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition-colors w-full md:w-auto"><?= $content['form']['submit'] ?? 'Send Message' ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
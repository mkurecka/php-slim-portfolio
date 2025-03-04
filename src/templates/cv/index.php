<section class="py-12 bg-gradient-to-r from-indigo-600 to-blue-600 text-white">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-2"><?= htmlspecialchars($cv['name']) ?></h1>
        <h2 class="text-2xl"><?= htmlspecialchars($cv['title']) ?></h2>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="flex flex-col md:flex-row">
            <!-- Left column (personal info and skills) -->
            <div class="w-full md:w-1/3 md:pr-8 mb-8 md:mb-0">
                <div class="mb-8">
                    <h3 class="text-xl font-bold mb-4 text-indigo-600 border-b border-gray-200 pb-2">Contact</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-gray-500 mr-3 w-5 text-center"></i>
                            <a href="mailto:<?= htmlspecialchars($cv['contact']['email']) ?>" class="text-gray-700 hover:text-indigo-600 transition-colors"><?= htmlspecialchars($cv['contact']['email']) ?></a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone text-gray-500 mr-3 w-5 text-center"></i>
                            <span class="text-gray-700"><?= htmlspecialchars($cv['contact']['phone']) ?></span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-globe text-gray-500 mr-3 w-5 text-center"></i>
                            <a href="https://<?= htmlspecialchars($cv['contact']['website']) ?>" target="_blank" class="text-gray-700 hover:text-indigo-600 transition-colors"><?= htmlspecialchars($cv['contact']['website']) ?></a>
                        </li>
                        <li class="flex items-center">
                            <i class="fab fa-linkedin text-gray-500 mr-3 w-5 text-center"></i>
                            <a href="https://<?= htmlspecialchars($cv['contact']['linkedin']) ?>" target="_blank" class="text-gray-700 hover:text-indigo-600 transition-colors"><?= htmlspecialchars($cv['contact']['linkedin']) ?></a>
                        </li>
                        <li class="flex items-center">
                            <i class="fab fa-github text-gray-500 mr-3 w-5 text-center"></i>
                            <a href="https://<?= htmlspecialchars($cv['contact']['github']) ?>" target="_blank" class="text-gray-700 hover:text-indigo-600 transition-colors"><?= htmlspecialchars($cv['contact']['github']) ?></a>
                        </li>
                    </ul>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-bold mb-4 text-indigo-600 border-b border-gray-200 pb-2">Skills</h3>
                    <?php foreach ($cv['skills'] as $skillCategory): ?>
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-800 mb-2"><?= htmlspecialchars($skillCategory['category']) ?></h4>
                        <div class="flex flex-wrap">
                            <?php foreach ($skillCategory['items'] as $skill): ?>
                            <span class="bg-gray-100 text-gray-700 text-sm rounded-full px-3 py-1 mb-2 mr-2"><?= htmlspecialchars($skill) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-bold mb-4 text-indigo-600 border-b border-gray-200 pb-2">Languages</h3>
                    <ul class="space-y-2">
                        <?php foreach ($cv['languages'] as $language): ?>
                        <li class="flex justify-between">
                            <span class="text-gray-700 font-medium"><?= htmlspecialchars($language['language']) ?></span>
                            <span class="text-gray-500"><?= htmlspecialchars($language['proficiency']) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Right column (experience, education, etc.) -->
            <div class="w-full md:w-2/3">
                <div class="mb-8">
                    <h3 class="text-xl font-bold mb-6 text-indigo-600 border-b border-gray-200 pb-2">Professional Summary</h3>
                    <p class="text-gray-700 leading-relaxed"><?= htmlspecialchars($cv['summary']) ?></p>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-bold mb-6 text-indigo-600 border-b border-gray-200 pb-2">Work Experience</h3>
                    <?php foreach ($cv['experience'] as $job): ?>
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-2">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($job['position']) ?></h4>
                                <div class="text-gray-600"><?= htmlspecialchars($job['company']) ?>, <?= htmlspecialchars($job['location']) ?></div>
                            </div>
                            <div class="text-gray-500 mt-1 md:mt-0"><?= htmlspecialchars($job['period']) ?></div>
                        </div>
                        <p class="text-gray-700 mb-3"><?= htmlspecialchars($job['description']) ?></p>
                        <?php if (!empty($job['achievements'])): ?>
                        <ul class="list-disc list-inside text-gray-700 space-y-1 pl-2">
                            <?php foreach ($job['achievements'] as $achievement): ?>
                            <li><?= htmlspecialchars($achievement) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-bold mb-6 text-indigo-600 border-b border-gray-200 pb-2">Education</h3>
                    <?php foreach ($cv['education'] as $education): ?>
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-2">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($education['degree']) ?></h4>
                                <div class="text-gray-600"><?= htmlspecialchars($education['institution']) ?>, <?= htmlspecialchars($education['location']) ?></div>
                            </div>
                            <div class="text-gray-500 mt-1 md:mt-0"><?= htmlspecialchars($education['year']) ?></div>
                        </div>
                        <?php if (!empty($education['details'])): ?>
                        <p class="text-gray-700"><?= htmlspecialchars($education['details']) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-bold mb-6 text-indigo-600 border-b border-gray-200 pb-2">Certifications</h3>
                    <ul class="space-y-3">
                        <?php foreach ($cv['certifications'] as $certification): ?>
                        <li class="flex justify-between">
                            <span class="text-gray-700 font-medium"><?= htmlspecialchars($certification['name']) ?></span>
                            <span class="text-gray-500"><?= htmlspecialchars($certification['issuer']) ?>, <?= htmlspecialchars($certification['year']) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-bold mb-6 text-indigo-600 border-b border-gray-200 pb-2">Projects</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($cv['projects'] as $project): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2"><?= htmlspecialchars($project['name']) ?></h4>
                            <p class="text-gray-700 mb-3"><?= htmlspecialchars($project['description']) ?></p>
                            <div class="mb-3">
                                <?php foreach ($project['technologies'] as $tech): ?>
                                <span class="inline-block bg-gray-100 text-gray-700 text-xs rounded-full px-2 py-1 mb-1 mr-1"><?= htmlspecialchars($tech) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <a href="https://<?= htmlspecialchars($project['url']) ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 transition-colors text-sm flex items-center">
                                <i class="fas fa-external-link-alt mr-1"></i> View Project
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4 text-center">
        <a href="/contact" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition-colors">Contact Me</a>
    </div>
</section>
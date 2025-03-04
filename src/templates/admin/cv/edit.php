<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit CV</h2>
    
    <?php if (isset($success)): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p><?= $success ?></p>
        </div>
    <?php endif; ?>
    
    <form action="/admin/cv/update" method="post" class="space-y-8">
        <!-- Personal Information -->
        <section>
            <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b border-gray-200">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-gray-700 font-medium mb-2">Full Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($cv['name']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label for="title" class="block text-gray-700 font-medium mb-2">Professional Title</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($cv['title']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
            </div>
            
            <div class="mt-4">
                <label for="summary" class="block text-gray-700 font-medium mb-2">Professional Summary</label>
                <textarea id="summary" name="summary" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"><?= htmlspecialchars($cv['summary']) ?></textarea>
            </div>
        </section>
        
        <!-- Contact Information -->
        <section>
            <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b border-gray-200">Contact Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" id="email" name="contact[email]" value="<?= htmlspecialchars($cv['contact']['email']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label for="phone" class="block text-gray-700 font-medium mb-2">Phone</label>
                    <input type="text" id="phone" name="contact[phone]" value="<?= htmlspecialchars($cv['contact']['phone']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label for="website" class="block text-gray-700 font-medium mb-2">Website</label>
                    <input type="text" id="website" name="contact[website]" value="<?= htmlspecialchars($cv['contact']['website']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label for="linkedin" class="block text-gray-700 font-medium mb-2">LinkedIn</label>
                    <input type="text" id="linkedin" name="contact[linkedin]" value="<?= htmlspecialchars($cv['contact']['linkedin']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label for="github" class="block text-gray-700 font-medium mb-2">GitHub</label>
                    <input type="text" id="github" name="contact[github]" value="<?= htmlspecialchars($cv['contact']['github']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
            </div>
        </section>
                
        <!-- Skills -->
        <section>
            <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b border-gray-200">Skills</h3>
            <p class="text-gray-500 mb-4">For each skill category, list the skills separated by commas.</p>
            
            <?php foreach ($cv['skills'] as $index => $skillCategory): ?>
            <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                <div class="mb-3">
                    <label for="skill_category_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Category</label>
                    <input type="text" id="skill_category_<?= $index ?>" name="skills[<?= $index ?>][category]" value="<?= htmlspecialchars($skillCategory['category']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label for="skill_items_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Skills</label>
                    <input type="text" id="skill_items_<?= $index ?>" name="skills[<?= $index ?>][items]" value="<?= htmlspecialchars(implode(', ', $skillCategory['items'])) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
            </div>
            <?php endforeach; ?>
        </section>
        
        <!-- Work Experience -->
        <section>
            <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b border-gray-200">Work Experience</h3>
            
            <?php foreach ($cv['experience'] as $index => $job): ?>
            <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                    <div>
                        <label for="job_position_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Position</label>
                        <input type="text" id="job_position_<?= $index ?>" name="experience[<?= $index ?>][position]" value="<?= htmlspecialchars($job['position']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="job_company_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Company</label>
                        <input type="text" id="job_company_<?= $index ?>" name="experience[<?= $index ?>][company]" value="<?= htmlspecialchars($job['company']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="job_location_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Location</label>
                        <input type="text" id="job_location_<?= $index ?>" name="experience[<?= $index ?>][location]" value="<?= htmlspecialchars($job['location']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="job_period_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Period</label>
                        <input type="text" id="job_period_<?= $index ?>" name="experience[<?= $index ?>][period]" value="<?= htmlspecialchars($job['period']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="job_description_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea id="job_description_<?= $index ?>" name="experience[<?= $index ?>][description]" rows="2" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"><?= htmlspecialchars($job['description']) ?></textarea>
                </div>
                <div>
                    <label for="job_achievements_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Key Achievements (one per line)</label>
                    <textarea id="job_achievements_<?= $index ?>" name="experience[<?= $index ?>][achievements]" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"><?= htmlspecialchars(implode("\n", $job['achievements'])) ?></textarea>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
        
        <!-- Education -->
        <section>
            <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b border-gray-200">Education</h3>
            
            <?php foreach ($cv['education'] as $index => $education): ?>
            <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                    <div>
                        <label for="edu_degree_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Degree</label>
                        <input type="text" id="edu_degree_<?= $index ?>" name="education[<?= $index ?>][degree]" value="<?= htmlspecialchars($education['degree']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="edu_institution_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Institution</label>
                        <input type="text" id="edu_institution_<?= $index ?>" name="education[<?= $index ?>][institution]" value="<?= htmlspecialchars($education['institution']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="edu_location_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Location</label>
                        <input type="text" id="edu_location_<?= $index ?>" name="education[<?= $index ?>][location]" value="<?= htmlspecialchars($education['location']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="edu_year_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Year</label>
                        <input type="text" id="edu_year_<?= $index ?>" name="education[<?= $index ?>][year]" value="<?= htmlspecialchars($education['year']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
                <div>
                    <label for="edu_details_<?= $index ?>" class="block text-gray-700 font-medium mb-2">Details</label>
                    <input type="text" id="edu_details_<?= $index ?>" name="education[<?= $index ?>][details]" value="<?= htmlspecialchars($education['details']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
            </div>
            <?php endforeach; ?>
        </section>
        
        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                Save Changes
            </button>
        </div>
    </form>
</div>
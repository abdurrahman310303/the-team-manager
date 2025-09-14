<?php
$title = 'Edit Daily Report - Team Manager';
$currentPage = 'daily-reports';

// Check if u// For non-admin users, use the improved layout
ob_start();
?>

<div class="px-4 py-6 sm:px-0">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Daily Report</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Update your daily work activities, challenges, and plans.
                </p>
            </div>
            
            <form action="/daily-reports/<?= $report['id'] ?>/update" method="POST" class="px-6 py-6">
                <div class="space-y-8">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                            <input type="date" name="date" id="date" value="<?= $report['report_date'] ?>"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="hours_worked" class="block text-sm font-medium text-gray-700 mb-2">Hours Worked *</label>
                            <input type="number" name="hours_worked" id="hours_worked" step="0.5" min="0" max="24" required
                                value="<?= $report['hours_worked'] ?>"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="8.0">
                        </div>
                    </div>

                    <!-- Work Completed -->
                    <div>
                        <label for="work_completed" class="block text-sm font-medium text-gray-700 mb-2">Work Completed Today *</label>
                        <textarea name="work_completed" id="work_completed" rows="5" required
                            class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                            placeholder="Describe what you accomplished today..."><?= htmlspecialchars($report['work_completed'] ?? '') ?></textarea>
                    </div>

                    <!-- Challenges and Plans -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="challenges_faced" class="block text-sm font-medium text-gray-700 mb-2">Challenges Faced</label>
                            <textarea name="challenges_faced" id="challenges_faced" rows="4"
                                class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Any challenges or obstacles you encountered..."><?= htmlspecialchars($report['challenges_faced'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label for="next_plans" class="block text-sm font-medium text-gray-700 mb-2">Next Plans</label>
                            <textarea name="next_plans" id="next_plans" rows="4"
                                class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="What do you plan to work on next..."><?= htmlspecialchars($report['next_plans'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <?php if ($report['report_type'] === 'bd'): ?>
                    <!-- Business Development Metrics -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Business Development Metrics</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="leads_generated" class="block text-sm font-medium text-gray-700 mb-2">Leads Generated</label>
                                <input type="number" name="leads_generated" id="leads_generated" min="0"
                                    value="<?= $report['leads_generated'] ?? 0 ?>"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="proposals_submitted" class="block text-sm font-medium text-gray-700 mb-2">Proposals Submitted</label>
                                <input type="number" name="proposals_submitted" id="proposals_submitted" min="0"
                                    value="<?= $report['proposals_submitted'] ?? 0 ?>"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="projects_locked" class="block text-sm font-medium text-gray-700 mb-2">Projects Locked</label>
                                <input type="number" name="projects_locked" id="projects_locked" min="0"
                                    value="<?= $report['projects_locked'] ?? 0 ?>"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="revenue_generated" class="block text-sm font-medium text-gray-700 mb-2">Revenue Generated</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="revenue_generated" id="revenue_generated" step="0.01" min="0"
                                        value="<?= $report['revenue_generated'] ?? 0 ?>"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Additional Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                            placeholder="Any additional notes or comments..."><?= htmlspecialchars($report['notes'] ?? '') ?></textarea>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="/daily-reports/<?= $report['id'] ?>" 
                        class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>s admin and use admin layout
if (isset($user) && $user['role_name'] === 'admin') {
    ob_start();
?>

<div class="page-header">
    <h1 class="page-title">Edit Daily Report</h1>
    <p class="page-subtitle">Update daily work activities, challenges, and plans</p>
</div>

<form action="/daily-reports/<?= $report['id'] ?>/update" method="POST" class="form-container">
    <div class="form-sections">
        <!-- Basic Information Section -->
        <div class="form-section">
            <h2 class="section-title">Report Information</h2>
            <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="date">Date *</label>
                    <input type="date" id="date" name="date" value="<?= $report['report_date'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="hours_worked">Hours Worked *</label>
                    <input type="number" id="hours_worked" name="hours_worked" step="0.5" min="0" max="24" value="<?= $report['hours_worked'] ?>" placeholder="8.0" required>
                </div>
            </div>
        </div>

        <!-- Work Details Section -->
        <div class="form-section">
            <h2 class="section-title">Work Details</h2>
            <div class="form-group">
                <label for="work_completed">Work Completed Today *</label>
                <textarea id="work_completed" name="work_completed" rows="5" placeholder="Describe what you accomplished today..." required><?= htmlspecialchars($report['work_completed'] ?? '') ?></textarea>
            </div>
            <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="challenges_faced">Challenges Faced</label>
                    <textarea id="challenges_faced" name="challenges_faced" rows="4" placeholder="Any challenges or obstacles you encountered..."><?= htmlspecialchars($report['challenges_faced'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="next_plans">Next Plans</label>
                    <textarea id="next_plans" name="next_plans" rows="4" placeholder="What do you plan to work on next..."><?= htmlspecialchars($report['next_plans'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <?php if ($report['report_type'] === 'bd'): ?>
        <!-- Business Development Metrics Section -->
        <div class="form-section">
            <h2 class="section-title">Business Development Metrics</h2>
            <div class="form-grid" style="grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="form-group">
                    <label for="leads_generated">Leads Generated</label>
                    <input type="number" id="leads_generated" name="leads_generated" min="0" value="<?= $report['leads_generated'] ?? 0 ?>" placeholder="0">
                </div>
                <div class="form-group">
                    <label for="proposals_submitted">Proposals Submitted</label>
                    <input type="number" id="proposals_submitted" name="proposals_submitted" min="0" value="<?= $report['proposals_submitted'] ?? 0 ?>" placeholder="0">
                </div>
                <div class="form-group">
                    <label for="projects_locked">Projects Locked</label>
                    <input type="number" id="projects_locked" name="projects_locked" min="0" value="<?= $report['projects_locked'] ?? 0 ?>" placeholder="0">
                </div>
                <div class="form-group">
                    <label for="revenue_generated">Revenue Generated ($)</label>
                    <input type="number" id="revenue_generated" name="revenue_generated" step="0.01" min="0" value="<?= $report['revenue_generated'] ?? 0 ?>" placeholder="0.00">
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Additional Information Section -->
        <div class="form-section">
            <h2 class="section-title">Additional Information</h2>
            <div class="form-group">
                <label for="notes">Additional Notes</label>
                <textarea id="notes" name="notes" rows="4" placeholder="Any additional notes or comments..."><?= htmlspecialchars($report['notes'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <a href="/daily-reports/<?= $report['id'] ?>" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Update Report</button>
    </div>
</form>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../admin_layout.php';
    return;
}

// For non-admin users, use the improved layout
ob_start();
?>

<div class="px-4 py-6 sm:px-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Daily Report</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Update your daily work activities, challenges, and plans.
                </p>
            </div>
        </div>
        
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="/daily-reports/<?= $report['id'] ?>/update" method="POST">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" id="date" value="<?= $report['report_date'] ?>"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="work_completed" class="block text-sm font-medium text-gray-700">Work Completed Today *</label>
                            <textarea name="work_completed" id="work_completed" rows="4" required
                                class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Describe what you accomplished today..."><?= htmlspecialchars($report['work_completed'] ?? '') ?></textarea>
                        </div>

                        <div>
                            <label for="hours_worked" class="block text-sm font-medium text-gray-700">Hours Worked *</label>
                            <input type="number" name="hours_worked" id="hours_worked" step="0.5" min="0" max="24" required
                                value="<?= $report['hours_worked'] ?>"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="8.0">
                        </div>

                        <div>
                            <label for="challenges_faced" class="block text-sm font-medium text-gray-700">Challenges Faced</label>
                            <textarea name="challenges_faced" id="challenges_faced" rows="3"
                                class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Any challenges or obstacles you encountered..."><?= htmlspecialchars($report['challenges_faced'] ?? '') ?></textarea>
                        </div>

                        <div>
                            <label for="next_plans" class="block text-sm font-medium text-gray-700">Next Plans</label>
                            <textarea name="next_plans" id="next_plans" rows="3"
                                class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="What do you plan to work on next..."><?= htmlspecialchars($report['next_plans'] ?? '') ?></textarea>
                        </div>

                        <?php if ($report['report_type'] === 'bd'): ?>
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Business Development Metrics</h4>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="leads_generated" class="block text-sm font-medium text-gray-700">Leads Generated</label>
                                    <input type="number" name="leads_generated" id="leads_generated" min="0"
                                        value="<?= $report['leads_generated'] ?? 0 ?>"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <div>
                                    <label for="proposals_submitted" class="block text-sm font-medium text-gray-700">Proposals Submitted</label>
                                    <input type="number" name="proposals_submitted" id="proposals_submitted" min="0"
                                        value="<?= $report['proposals_submitted'] ?? 0 ?>"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <div>
                                    <label for="projects_locked" class="block text-sm font-medium text-gray-700">Projects Locked</label>
                                    <input type="number" name="projects_locked" id="projects_locked" min="0"
                                        value="<?= $report['projects_locked'] ?? 0 ?>"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <div>
                                    <label for="revenue_generated" class="block text-sm font-medium text-gray-700">Revenue Generated</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" name="revenue_generated" id="revenue_generated" step="0.01" min="0"
                                            value="<?= $report['revenue_generated'] ?? 0 ?>"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Any additional notes or comments..."><?= htmlspecialchars($report['notes'] ?? '') ?></textarea>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                        <a href="/daily-reports/<?= $report['id'] ?>" 
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

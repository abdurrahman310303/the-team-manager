<?php
$title = 'Edit Project - Team Manager';
$currentPage = 'projects';

// Check if user is admin and use admin layout
if (isset($user) && $user['role_name'] === 'admin') {
    ob_start();
?>

<div class="page-header">
    <h1 class="page-title">Edit Project</h1>
    <p class="page-subtitle">Update the project details and manage team assignments</p>
</div>

<div class="form-container">
    <form action="/projects/update/<?= $project['id'] ?>" method="POST">
        
        <!-- Basic Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Basic Information
            </h3>
            
            <div class="form-group">
                <label for="name" class="form-label">Project Name *</label>
                <input type="text" name="name" id="name" required class="form-input" 
                       value="<?= htmlspecialchars($project['name']) ?>"
                       placeholder="Enter project name">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Project Description</label>
                <textarea name="description" id="description" class="form-textarea" rows="4"
                          placeholder="Describe the project goals, scope, and key deliverables..."><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="client_name" class="form-label">Client Name</label>
                    <input type="text" name="client_name" id="client_name" class="form-input" 
                           value="<?= htmlspecialchars($project['client_name'] ?? '') ?>"
                           placeholder="Enter client or company name">
                </div>

                <div class="form-group">
                    <label for="budget" class="form-label">Project Budget</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #666666; font-weight: 500;">$</span>
                        <input type="number" name="budget" id="budget" step="0.01" min="0" class="form-input" 
                               style="padding-left: 24px;" 
                               value="<?= $project['budget'] ?? '' ?>"
                               placeholder="0.00">
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Project Timeline
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-input"
                           value="<?= $project['start_date'] ?? '' ?>">
                </div>

                <div class="form-group">
                    <label for="end_date" class="form-label">Expected End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-input"
                           value="<?= $project['end_date'] ?? '' ?>">
                </div>
            </div>
        </div>

        <!-- Management Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Project Management
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="project_manager_id" class="form-label">Project Manager</label>
                    <select name="project_manager_id" id="project_manager_id" class="form-select">
                        <option value="">Select project manager</option>
                        <?php if (isset($managers) && !empty($managers)): ?>
                            <?php foreach ($managers as $manager): ?>
                                <option value="<?= $manager['id'] ?>" <?= $project['project_manager_id'] == $manager['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($manager['name']) ?> 
                                    (<?= htmlspecialchars($manager['role_display_name'] ?? ucfirst($manager['role_name'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Project Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="planning" <?= $project['status'] === 'planning' ? 'selected' : '' ?>>Planning</option>
                        <option value="in_progress" <?= $project['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="on_hold" <?= $project['status'] === 'on_hold' ? 'selected' : '' ?>>On Hold</option>
                        <option value="completed" <?= $project['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $project['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Team Assignment Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Team Assignment
            </h3>
            
            <div class="form-group">
                <label class="form-label">Assign Team Members</label>
                <div style="border: 1px solid #cccccc; border-radius: 4px; padding: 15px; max-height: 200px; overflow-y: auto; background: #f9f9f9;">
                    <?php if (isset($users) && !empty($users)): ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px;">
                            <?php 
                            // Get array of assigned user IDs for easy checking
                            $assignedUserIds = array_column($assignedUsers ?? [], 'id');
                            ?>
                            <?php foreach ($users as $teamUser): ?>
                                <label style="display: flex; align-items: center; padding: 8px; background: #ffffff; border: 1px solid #e5e5e5; border-radius: 3px; cursor: pointer; transition: all 0.2s;">
                                    <input type="checkbox" name="assigned_users[]" value="<?= $teamUser['id'] ?>" 
                                           <?= in_array($teamUser['id'], $assignedUserIds) ? 'checked' : '' ?>
                                           style="margin-right: 8px;">
                                    <div>
                                        <div style="font-weight: 500; color: #1a1a1a;">
                                            <?= htmlspecialchars($teamUser['name']) ?>
                                        </div>
                                        <div style="font-size: 12px; color: #666666;">
                                            <?= htmlspecialchars($teamUser['role_display_name'] ?? ucfirst($teamUser['role_name'])) ?>
                                        </div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p style="color: #666666; text-align: center; margin: 20px 0;">No team members available for assignment</p>
                    <?php endif; ?>
                </div>
                <p style="color: #666666; font-size: 12px; margin-top: 8px;">
                    Select team members who will work on this project. Changes will be saved when you update the project.
                </p>
            </div>
        </div>

        <!-- Actions -->
        <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #e5e5e5;">
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="/projects/<?= $project['id'] ?>" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Update Project
                </button>
            </div>
        </div>
    </form>
</div>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../admin_layout.php';
    return;
}

// For non-admin users, use the original layout
ob_start();
?>

<div class="px-4 py-6 sm:px-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Project</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Update the project details below.
                </p>
            </div>
        </div>
        
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="/projects/update/<?= $project['id'] ?>" method="POST">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
                            <input type="text" name="name" id="name" required
                                value="<?= htmlspecialchars($project['name']) ?>"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Enter project name">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Project description..."><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="client_name" class="block text-sm font-medium text-gray-700">Client Name</label>
                                <input type="text" name="client_name" id="client_name"
                                    value="<?= htmlspecialchars($project['client_name'] ?? '') ?>"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Client name">
                            </div>

                            <div>
                                <label for="budget" class="block text-sm font-medium text-gray-700">Budget</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="budget" id="budget" step="0.01" min="0"
                                        value="<?= $project['budget'] ?? '' ?>"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                        placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="<?= $project['start_date'] ?? '' ?>"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date"
                                    value="<?= $project['end_date'] ?? '' ?>"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="project_manager_id" class="block text-sm font-medium text-gray-700">Project Manager *</label>
                                <select name="project_manager_id" id="project_manager_id" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select a project manager</option>
                                    <?php if (isset($users) && !empty($users)): ?>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?= $user['id'] ?>" <?= $project['project_manager_id'] == $user['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['role_display_name'] ?? ucfirst($user['role_name'])) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">No users available</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="planning" <?= $project['status'] === 'planning' ? 'selected' : '' ?>>Planning</option>
                                    <option value="in_progress" <?= $project['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="completed" <?= $project['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="on_hold" <?= $project['status'] === 'on_hold' ? 'selected' : '' ?>>On Hold</option>
                                    <option value="cancelled" <?= $project['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Assign Team Members</label>
                            <div class="space-y-3 max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                                <?php if (isset($users) && !empty($users)): ?>
                                    <?php 
                                    // Get array of assigned user IDs for easy checking
                                    $assignedUserIds = array_column($assignedUsers ?? [], 'id');
                                    ?>
                                    <?php foreach ($users as $user): ?>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="assigned_users[]" value="<?= $user['id'] ?>" 
                                                id="user_<?= $user['id'] ?>"
                                                <?= in_array($user['id'], $assignedUserIds) ? 'checked' : '' ?>
                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="user_<?= $user['id'] ?>" class="ml-3 text-sm text-gray-700">
                                                <?= htmlspecialchars($user['name']) ?> 
                                                <span class="text-xs text-gray-500">(<?= htmlspecialchars($user['role_display_name'] ?? ucfirst($user['role_name'])) ?>)</span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500">No users available</p>
                                <?php endif; ?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Select team members who will work on this project.</p>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                        <a href="/projects/<?= $project['id'] ?>" 
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Project
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>

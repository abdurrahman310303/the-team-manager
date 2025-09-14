<?php
$title = 'Edit User - Team Manager';
$currentPage = 'users';

// Get user from session for admin layout detection
$user = Session::get('user');

// Check if user is admin and use admin layout
if (isset($user) && $user['role_name'] === 'admin') {
    ob_start();
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1 class="page-title">Edit User</h1>
            <p class="page-subtitle">Update user account information and role</p>
        </div>
        <div>
            <a href="/users/<?= $targetUser['id'] ?>" class="btn btn-secondary">
                <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"></path>
                </svg>
                Back to User
            </a>
        </div>
    </div>
</div>

<div class="form-container">
    <form action="/users/<?= $targetUser['id'] ?>/update" method="POST">
        <!-- User Avatar and Basic Info -->
        <div style="display: flex; align-items: center; padding: 20px; background: #f8f9fa; border-radius: 8px; margin-bottom: 30px;">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 20px; font-weight: 700; font-size: 24px;">
                <?= strtoupper(substr($targetUser['name'], 0, 1)) ?>
            </div>
            <div>
                <h3 style="margin: 0 0 4px 0; font-size: 18px; font-weight: 600;">
                    Editing: <?= htmlspecialchars($targetUser['name']) ?>
                </h3>
                <p style="margin: 0; color: #666; font-size: 14px;">
                    User ID: #<?= $targetUser['id'] ?> • Member since <?= date('M j, Y', strtotime($targetUser['created_at'])) ?>
                </p>
            </div>
        </div>

        <!-- Form Fields -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" required class="form-input"
                    placeholder="Enter full name"
                    value="<?= htmlspecialchars($targetUser['name']) ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" required class="form-input"
                    placeholder="user@example.com"
                    value="<?= htmlspecialchars($targetUser['email']) ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label">User Role *</label>
                <select name="role_id" required class="form-select">
                    <option value="">Select role</option>
                    <?php if (isset($roles) && is_array($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= $targetUser['role_id'] == $role['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($role['display_name'] ?? ucfirst($role['name'])) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-input"
                    placeholder="Leave blank to keep current password">
                <p style="font-size: 12px; color: #666; margin: 4px 0 0 0;">
                    Only enter a new password if you want to change it
                </p>
            </div>
        </div>

        <!-- Current Role Display -->
        <div class="form-group">
            <label class="form-label">Current Role</label>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="badge <?php
                    switch($targetUser['role_name']) {
                        case 'admin': echo 'badge-approved'; break;
                        case 'developer': 
                        case 'bd': 
                        case 'investor': echo 'badge-pending'; break;
                        default: echo 'badge-pending';
                    }
                ?>">
                    <?= htmlspecialchars($targetUser['role_display_name'] ?? ucfirst($targetUser['role_name'])) ?>
                </span>
                <span style="color: #666; font-size: 14px;">
                    • Member since <?= date('F j, Y', strtotime($targetUser['created_at'])) ?>
                </span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/users/<?= $targetUser['id'] ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                    <path d="M5 12l5 5L20 7"/>
                </svg>
                Update User
            </button>
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
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
                <p class="mt-2 text-gray-600">Update user account information and role</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/users/<?= $targetUser['id'] ?>" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"></path>
                    </svg>
                    Back to User
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Current User Info -->
            <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-16 w-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                            <span class="text-xl font-bold text-white">
                                <?= strtoupper(substr($targetUser['name'], 0, 1)) ?>
                            </span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-white">Editing: <?= htmlspecialchars($targetUser['name']) ?></h2>
                        <p class="text-indigo-100 text-sm">
                            User ID: #<?= $targetUser['id'] ?> • Member since <?= date('M j, Y', strtotime($targetUser['created_at'])) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="/users/<?= $targetUser['id'] ?>/update" method="POST">
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                            <input type="text" name="name" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Enter full name"
                                value="<?= htmlspecialchars($targetUser['name']) ?>">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                            <input type="email" name="email" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="user@example.com"
                                value="<?= htmlspecialchars($targetUser['email']) ?>">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700">User Role *</label>
                            <select name="role_id" required
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select role</option>
                                <?php if (isset($roles) && is_array($roles)): ?>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id'] ?>" <?= $targetUser['role_id'] == $role['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($role['display_name'] ?? ucfirst($role['name'])) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Leave blank to keep current password">
                            <p class="mt-1 text-xs text-gray-500">
                                Only enter a new password if you want to change it
                            </p>
                        </div>
                    </div>

                    <!-- Current Role Display -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Role</label>
                        <div class="mt-1 flex items-center space-x-3">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                <?php
                                switch($targetUser['role_name']) {
                                    case 'admin': echo 'bg-red-100 text-red-800'; break;
                                    case 'developer': echo 'bg-blue-100 text-blue-800'; break;
                                    case 'bd': echo 'bg-green-100 text-green-800'; break;
                                    case 'investor': echo 'bg-purple-100 text-purple-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= htmlspecialchars($targetUser['role_display_name'] ?? ucfirst($targetUser['role_name'])) ?>
                            </span>
                            <span class="text-sm text-gray-500">
                                Member since <?= date('F j, Y', strtotime($targetUser['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                    <a href="/users/<?= $targetUser['id'] ?>" 
                        class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

<?php
$title = 'User Details - Team Manager';
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
            <h1 class="page-title">User Details</h1>
            <p class="page-subtitle">View user information and account details</p>
        </div>
        <div>
            <a href="/users" class="btn btn-secondary">
                <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"></path>
                </svg>
                Back to Users
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div style="display: flex; align-items: center; padding: 30px; border-bottom: 1px solid #e5e5e5;">
        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 24px; font-weight: 700; font-size: 32px;">
            <?= strtoupper(substr($targetUser['name'], 0, 1)) ?>
        </div>
        <div style="flex: 1;">
            <h2 style="margin: 0 0 8px 0; font-size: 28px; font-weight: 600; color: #1a1a1a;">
                <?= htmlspecialchars($targetUser['name']) ?>
            </h2>
            <p style="margin: 0 0 8px 0; color: #666666; font-size: 16px;">
                <?= htmlspecialchars($targetUser['email']) ?>
            </p>
            <span class="badge <?php
                switch($targetUser['role_name']) {
                    case 'admin': echo 'badge-approved'; break;
                    case 'developer': 
                    case 'bd': 
                    case 'investor': echo 'badge-pending'; break;
                    default: echo 'badge-pending';
                }
            ?>" style="font-size: 14px;">
                <?= htmlspecialchars($targetUser['role_display_name'] ?? ucfirst($targetUser['role_name'])) ?>
            </span>
        </div>
    </div>

    <div style="padding: 30px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            <!-- Account Information -->
            <div>
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #1a1a1a;">Account Information</h3>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 4px;">Full Name</label>
                    <p style="margin: 0; color: #666666; font-size: 16px;"><?= htmlspecialchars($targetUser['name']) ?></p>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 4px;">Email Address</label>
                    <p style="margin: 0; color: #666666; font-size: 16px;"><?= htmlspecialchars($targetUser['email']) ?></p>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 4px;">User Role</label>
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
                </div>
            </div>

            <!-- System Information -->
            <div>
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #1a1a1a;">System Information</h3>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 4px;">User ID</label>
                    <p style="margin: 0; color: #666666; font-size: 16px; font-family: monospace;">#<?= $targetUser['id'] ?></p>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 4px;">Member Since</label>
                    <p style="margin: 0; color: #666666; font-size: 16px;"><?= date('F j, Y', strtotime($targetUser['created_at'])) ?></p>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 4px;">Last Updated</label>
                    <p style="margin: 0; color: #666666; font-size: 16px;"><?= date('F j, Y g:i A', strtotime($targetUser['updated_at'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 30px; border-top: 1px solid #e5e5e5; margin-top: 30px;">
            <a href="/users" class="btn btn-secondary">Back to Users</a>
            <a href="/users/<?= $targetUser['id'] ?>/edit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit User
            </a>
            <?php if ($targetUser['id'] != Session::get('user')['id']): ?>
            <form action="/users/<?= $targetUser['id'] ?>/delete" method="POST" style="display: inline;" 
                  onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                <button type="submit" class="btn btn-danger">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                        <polyline points="3,6 5,6 21,6"/>
                        <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"/>
                    </svg>
                    Delete User
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
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
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-gray-900">User Profile</h1>
                <p class="mt-2 text-gray-600">View user account details and information</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/users" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"></path>
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>

        <!-- User Profile Card -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Profile Header -->
            <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-20 w-20 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                            <span class="text-2xl font-bold text-white">
                                <?= strtoupper(substr($targetUser['name'], 0, 1)) ?>
                            </span>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-white"><?= htmlspecialchars($targetUser['name']) ?></h2>
                        <p class="text-indigo-100"><?= htmlspecialchars($targetUser['email']) ?></p>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-white bg-opacity-20 text-white mt-2">
                            <?= htmlspecialchars($targetUser['role_display_name'] ?? ucfirst($targetUser['role_name'])) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($targetUser['name']) ?></dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($targetUser['email']) ?></dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">User Role</dt>
                        <dd class="mt-1">
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
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">User ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">#<?= $targetUser['id'] ?></dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= date('F j, Y', strtotime($targetUser['created_at'])) ?></dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= date('F j, Y g:i A', strtotime($targetUser['updated_at'])) ?></dd>
                    </div>
                </dl>
            </div>

            <!-- Actions -->
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                <a href="/users" 
                    class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Back to Users
                </a>
                <a href="/users/<?= $targetUser['id'] ?>/edit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit User
                </a>
                <?php if ($targetUser['id'] != Session::get('user')['id']): ?>
                <form action="/users/<?= $targetUser['id'] ?>/delete" method="POST" class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete User
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>

<?php
$title = 'Users - Team Manager';
$currentPage = 'users';
ob_start();
?>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Users</h1>
    <p class="page-subtitle">Manage system users and their roles</p>
</div>

<div style="margin-bottom: 30px;">
    <a href="/users/create" class="btn btn-primary">+ Add User</a>
</div>

<!-- Users Table -->
<?php if (empty($users)): ?>
    <div class="card" style="text-align: center; padding: 60px 40px;">
        <h3 style="font-size: 18px; font-weight: 500; color: #1a1a1a; margin-bottom: 12px;">No users found</h3>
        <p style="color: #666666; margin-bottom: 30px;">Get started by creating a new user.</p>
        <a href="/users/create" class="btn btn-primary">+ Add User</a>
    </div>
<?php else: ?>
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <div>
                            <div style="font-weight: 500; color: #1a1a1a;"><?= htmlspecialchars($user['name']) ?></div>
                            <div style="font-size: 14px; color: #666666;"><?= htmlspecialchars($user['email']) ?></div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-approved">
                            <?= htmlspecialchars($user['role_display_name'] ?? ucfirst($user['role_name'])) ?>
                        </span>
                    </td>
                    <td style="color: #666666; font-size: 14px;">
                        <?= date('M j, Y', strtotime($user['created_at'])) ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="/users/<?= $user['id'] ?>" class="btn btn-sm btn-secondary">View</a>
                            <?php if (Auth::hasRole('admin') || Auth::user()['id'] == $user['id']): ?>
                            <a href="/users/<?= $user['id'] ?>/edit" class="btn btn-sm">Edit</a>
                            <?php endif; ?>
                            <?php if (Auth::hasRole('admin') && Auth::user()['id'] != $user['id']): ?>
                            <form method="POST" action="/users/<?= $user['id'] ?>/delete" style="display: inline;" 
                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

<div class="px-4 py-6 sm:px-0">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900">Users</h1>
            <p class="mt-2 text-gray-600">Manage system users and their roles</p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="/users/create" 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                Add User
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <?php if (empty($users)): ?>
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No users</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new user.</p>
                    <div class="mt-6">
                        <a href="/users/create" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            Add User
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                                <span class="text-sm font-medium text-white">
                                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="/users/<?= $user['id'] ?>" class="hover:text-indigo-600">
                                                    <?= htmlspecialchars($user['name']) ?>
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($user['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php
                                        switch($user['role_name']) {
                                            case 'admin': echo 'bg-red-100 text-red-800'; break;
                                            case 'developer': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'bd': echo 'bg-green-100 text-green-800'; break;
                                            case 'investor': echo 'bg-purple-100 text-purple-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= htmlspecialchars($user['role_display_name'] ?? ucfirst($user['role_name'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M j, Y', strtotime($user['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2 justify-end">
                                        <a href="/users/<?= $user['id'] ?>" 
                                            class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="/users/<?= $user['id'] ?>/edit" 
                                            class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                        <?php if ($user['id'] != Session::get('user')['id']): ?>
                                        <form action="/users/<?= $user['id'] ?>/delete" method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
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

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

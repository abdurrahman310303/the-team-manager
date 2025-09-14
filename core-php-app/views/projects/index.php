<?php
$title = 'Projects - Team Manager';
$currentPage = 'projects';
ob_start();
?>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Projects</h1>
    <p class="page-subtitle">Manage your development projects</p>
</div>

<?php if (Auth::hasRole('admin')): ?>
<div style="margin-bottom: 30px;">
    <a href="/projects/create" class="btn btn-primary">+ Create Project</a>
</div>
<?php endif; ?>

<!-- Projects Table -->
<?php if (empty($projects ?? [])): ?>
    <div class="card" style="text-align: center; padding: 60px 40px;">
        <h3 style="font-size: 18px; font-weight: 500; color: #1a1a1a; margin-bottom: 12px;">No projects found</h3>
        <p style="color: #666666; margin-bottom: 30px;">
            <?php if (Auth::hasRole('admin')): ?>
                Get started by creating a new project.
            <?php else: ?>
                No projects have been assigned to you yet.
            <?php endif; ?>
        </p>
        <?php if (Auth::hasRole('admin')): ?>
        <a href="/projects/create" class="btn btn-primary">+ Create Project</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Status</th>
                    <th>Budget</th>
                    <th>Manager</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                <tr>
                    <td>
                        <div>
                            <div style="font-weight: 500; color: #1a1a1a;"><?= htmlspecialchars($project['name']) ?></div>
                            <div style="font-size: 14px; color: #666666;"><?= htmlspecialchars(substr($project['description'] ?? '', 0, 50)) ?><?= strlen($project['description'] ?? '') > 50 ? '...' : '' ?></div>
                        </div>
                    </td>
                    <td>
                        <span class="badge <?php
                        switch($project['status']) {
                            case 'planning': case 'in_progress': echo 'badge-pending'; break;
                            case 'completed': echo 'badge-approved'; break;
                            case 'on_hold': case 'cancelled': echo 'badge-rejected'; break;
                            default: echo 'badge-pending';
                        }
                        ?>">
                            <?= ucwords(str_replace('_', ' ', $project['status'])) ?>
                        </span>
                    </td>
                    <td style="color: #666666; font-size: 14px;">
                        <?= $project['budget'] ? '$' . number_format($project['budget'], 2) : 'Not set' ?>
                    </td>
                    <td style="color: #666666; font-size: 14px;">
                        <?= htmlspecialchars($project['project_manager_name'] ?? 'Not assigned') ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="/projects/<?= $project['id'] ?>" class="btn btn-sm btn-secondary">View</a>
                            <?php if (Auth::hasRole('admin')): ?>
                            <a href="/projects/<?= $project['id'] ?>/edit" class="btn btn-sm">Edit</a>
                            <form method="POST" action="/projects/<?= $project['id'] ?>/delete" style="display: inline;" 
                                  onsubmit="return confirm('Are you sure you want to delete this project?')">
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
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Projects</h1>
            <p class="mt-2 text-sm text-gray-700">A list of all projects in the system.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <?php if (User::isAdmin()): ?>
            <a href="/projects/create" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                Add Project
            </a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Project
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Manager
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Budget
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($projects as $project): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($project['name']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= htmlspecialchars(substr($project['description'] ?? '', 0, 100)) ?>
                                                <?= strlen($project['description'] ?? '') > 100 ? '...' : '' ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php
                                        switch($project['status']) {
                                            case 'planning': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'in_progress': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'completed': echo 'bg-green-100 text-green-800'; break;
                                            case 'on_hold': echo 'bg-gray-100 text-gray-800'; break;
                                            case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucwords(str_replace('_', ' ', $project['status'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($project['project_manager_name'] ?? 'Not assigned') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= $project['budget'] ? '$' . number_format($project['budget'], 2) : '-' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="/projects/<?= $project['id'] ?>" class="text-indigo-600 hover:text-indigo-900">
                                        View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

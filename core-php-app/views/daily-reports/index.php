<?php
$title = 'Daily Reports - Team Manager';
ob_start();
?>

<div class="px-4 py-6 sm:px-0">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Daily Reports</h1>
            <p class="mt-2 text-sm text-gray-700">Track daily work progress and activities.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="/daily-reports/create" 
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                Add Report
            </a>
        </div>
    </div>

    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <?php if (isset($user) && $user['role_name'] === 'admin'): ?>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <?php endif; ?>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Done</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Challenges</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($reports)): ?>
                            <tr>
                                <td colspan="<?= isset($user) && $user['role_name'] === 'admin' ? '6' : '5' ?>" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No daily reports found.
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($reports as $report): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= date('M j, Y', strtotime($report['report_date'])) ?>
                                </td>
                                <?php if (isset($user) && $user['role_name'] === 'admin'): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($report['user_name'] ?? 'Unknown') ?>
                                </td>
                                <?php endif; ?>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        <?= htmlspecialchars(substr($report['work_done'], 0, 100)) ?>
                                        <?= strlen($report['work_done']) > 100 ? '...' : '' ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= $report['hours_worked'] ?> hrs
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        <?php if ($report['challenges']): ?>
                                            <?= htmlspecialchars(substr($report['challenges'], 0, 50)) ?>
                                            <?= strlen($report['challenges']) > 50 ? '...' : '' ?>
                                        <?php else: ?>
                                            <span class="text-gray-400">None</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="/daily-reports/<?= $report['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                    <?php 
                                    $canEdit = (isset($user) && ($user['role_name'] === 'admin' || $report['user_id'] == $user['id']));
                                    if ($canEdit): 
                                    ?>
                                    <a href="/daily-reports/<?= $report['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <button onclick="deleteReport(<?= $report['id'] ?>)" class="text-red-600 hover:text-red-900">Delete</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteReport(id) {
    if (confirm('Are you sure you want to delete this daily report?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/daily-reports/${id}/delete`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>

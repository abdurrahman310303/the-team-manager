<?php
$title = 'Leads - Team Manager';
ob_start();
?>

<div class="px-4 py-6 sm:px-0">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Leads</h1>
            <p class="mt-2 text-sm text-gray-700">Manage your sales leads and prospects.</p>
        </div>
        <?php if (isset($user) && $user['role_name'] === 'admin'): ?>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="/leads/create" 
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                Add Lead
            </a>
        </div>
        <?php endif; ?>
    </div>

    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Potential Value</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($leads)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No leads found.
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($leads as $lead): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($lead['company_name']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($lead['contact_person']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($lead['email']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php
                                        switch($lead['status']) {
                                            case 'new': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'contacted': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'qualified': echo 'bg-green-100 text-green-800'; break;
                                            case 'proposal': echo 'bg-purple-100 text-purple-800'; break;
                                            case 'won': echo 'bg-green-100 text-green-800'; break;
                                            case 'lost': echo 'bg-red-100 text-red-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800'; break;
                                        }
                                        ?>">
                                        <?= ucfirst($lead['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($lead['source'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= $lead['potential_value'] ? '$' . number_format($lead['potential_value'], 2) : '-' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($lead['assigned_name'] ?? 'Unassigned') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="/leads/<?= $lead['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                    <?php if (isset($user) && $user['role_name'] === 'admin'): ?>
                                    <a href="/leads/<?= $lead['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <button onclick="deleteLead(<?= $lead['id'] ?>)" class="text-red-600 hover:text-red-900">Delete</button>
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

<?php if (isset($user) && $user['role_name'] === 'admin'): ?>
<script>
function deleteLead(id) {
    if (confirm('Are you sure you want to delete this lead?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/leads/${id}/delete`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>

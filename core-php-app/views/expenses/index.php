<?php
$title = 'Expenses - Team Manager';
ob_start();
?>

<div class="px-4 py-6 sm:px-0">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Expenses</h1>
            <p class="mt-2 text-sm text-gray-700">Manage and track expense reports.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="/expenses/create" 
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                Add Expense
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($expenses)): ?>
                            <tr>
                                <td colspan="<?= isset($user) && $user['role_name'] === 'admin' ? '7' : '6' ?>" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No expenses found.
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= date('M j, Y', strtotime($expense['expense_date'])) ?>
                                </td>
                                <?php if (isset($user) && $user['role_name'] === 'admin'): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($expense['user_name'] ?? 'Unknown') ?>
                                </td>
                                <?php endif; ?>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        <?= htmlspecialchars($expense['description']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <?= ucfirst(str_replace('_', ' ', $expense['category'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    $<?= number_format($expense['amount'], 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php
                                        switch($expense['status']) {
                                            case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'approved': echo 'bg-green-100 text-green-800'; break;
                                            case 'rejected': echo 'bg-red-100 text-red-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800'; break;
                                        }
                                        ?>">
                                        <?= ucfirst($expense['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="/expenses/<?= $expense['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                    <?php 
                                    $canEdit = (isset($user) && ($user['role_name'] === 'admin' || 
                                        ($expense['added_by'] == $user['id'] && $expense['status'] === 'pending')));
                                    if ($canEdit): 
                                    ?>
                                    <a href="/expenses/<?= $expense['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <?php endif; ?>
                                    <?php 
                                    $canDelete = (isset($user) && ($user['role_name'] === 'admin' || $expense['added_by'] == $user['id']));
                                    if ($canDelete): 
                                    ?>
                                    <button onclick="deleteExpense(<?= $expense['id'] ?>)" class="text-red-600 hover:text-red-900">Delete</button>
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

    <?php if (!empty($expenses)): ?>
    <div class="mt-6 bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Expense Summary</h3>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                <?php
                $totalPending = 0;
                $totalApproved = 0;
                $totalRejected = 0;
                
                foreach ($expenses as $expense) {
                    switch ($expense['status']) {
                        case 'pending':
                            $totalPending += $expense['amount'];
                            break;
                        case 'approved':
                            $totalApproved += $expense['amount'];
                            break;
                        case 'rejected':
                            $totalRejected += $expense['amount'];
                            break;
                    }
                }
                ?>
                
                <div class="bg-yellow-50 overflow-hidden rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                                    <span class="text-yellow-800 font-semibold text-sm">P</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dt class="text-sm font-medium text-yellow-800 truncate">Pending</dt>
                                <dd class="text-lg font-medium text-yellow-900">$<?= number_format($totalPending, 2) ?></dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 overflow-hidden rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-400 rounded-full flex items-center justify-center">
                                    <span class="text-green-800 font-semibold text-sm">A</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dt class="text-sm font-medium text-green-800 truncate">Approved</dt>
                                <dd class="text-lg font-medium text-green-900">$<?= number_format($totalApproved, 2) ?></dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 overflow-hidden rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-400 rounded-full flex items-center justify-center">
                                    <span class="text-red-800 font-semibold text-sm">R</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dt class="text-sm font-medium text-red-800 truncate">Rejected</dt>
                                <dd class="text-lg font-medium text-red-900">$<?= number_format($totalRejected, 2) ?></dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function deleteExpense(id) {
    if (confirm('Are you sure you want to delete this expense?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/expenses/${id}/delete`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>

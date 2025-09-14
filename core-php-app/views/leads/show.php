<?php
$title = 'Lead Details - Team Manager';
ob_start();
?>

<div class="px-4 py-6 sm:px-0">
    <div class="lg:flex lg:items-center lg:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                <?= htmlspecialchars($lead['company_name']) ?>
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
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
                </div>
                <?php if ($lead['potential_value']): ?>
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                    </svg>
                    Potential Value: $<?= number_format($lead['potential_value'], 2) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-5 flex lg:mt-0 lg:ml-4">
            <span class="sm:ml-3">
                <a href="/leads" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ← Back to Leads
                </a>
            </span>
        </div>
    </div>

    <div class="mt-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Lead Information</h3>
                
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Contact Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <?= htmlspecialchars($lead['contact_person']) ?>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="mailto:<?= htmlspecialchars($lead['email']) ?>" class="text-indigo-600 hover:text-indigo-500">
                                <?= htmlspecialchars($lead['email']) ?>
                            </a>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="tel:<?= htmlspecialchars($lead['phone']) ?>" class="text-indigo-600 hover:text-indigo-500">
                                <?= htmlspecialchars($lead['phone']) ?>
                            </a>
                        </dd>
                    </div>
                    
                    <?php if ($lead['source']): ?>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Lead Source</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <?= ucwords(str_replace('_', ' ', $lead['source'])) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <?= htmlspecialchars($lead['assigned_name'] ?? 'Unassigned') ?>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <?= date('M j, Y g:i A', strtotime($lead['created_at'])) ?>
                        </dd>
                    </div>
                </dl>

                <?php if ($lead['notes']): ?>
                <div class="mt-6">
                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">
                        <?= htmlspecialchars($lead['notes']) ?>
                    </dd>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

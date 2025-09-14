<?php
$title = 'Edit Expense - Team Manager';
$currentPage = 'expenses';

// Check if user is admin and use admin layout
if (isset($user) && $user['role_name'] === 'admin') {
    ob_start();
?>

<div class="page-header">
    <h1 class="page-title">Edit Expense</h1>
    <p class="page-subtitle">Update expense details and information</p>
</div>

<div class="form-container">
    <form action="/expenses/<?= $expense['id'] ?>/update" method="POST" enctype="multipart/form-data">
        
        <!-- Basic Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Expense Details
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="date" class="form-label">Expense Date *</label>
                    <input type="date" name="date" id="date" value="<?= $expense['expense_date'] ?>" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">Category *</label>
                    <select name="category" id="category" required class="form-select">
                        <option value="">Select category</option>
                        <option value="office_supplies" <?= $expense['category'] === 'office_supplies' ? 'selected' : '' ?>>Office Supplies</option>
                        <option value="travel" <?= $expense['category'] === 'travel' ? 'selected' : '' ?>>Travel</option>
                        <option value="meals" <?= $expense['category'] === 'meals' ? 'selected' : '' ?>>Meals & Entertainment</option>
                        <option value="equipment" <?= $expense['category'] === 'equipment' ? 'selected' : '' ?>>Equipment</option>
                        <option value="software" <?= $expense['category'] === 'software' ? 'selected' : '' ?>>Software/Subscriptions</option>
                        <option value="marketing" <?= $expense['category'] === 'marketing' ? 'selected' : '' ?>>Marketing</option>
                        <option value="training" <?= $expense['category'] === 'training' ? 'selected' : '' ?>>Training</option>
                        <option value="other" <?= $expense['category'] === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="title" class="form-label">Title *</label>
                <input type="text" name="title" id="title" required class="form-input" 
                       value="<?= htmlspecialchars($expense['title'] ?? '') ?>"
                       placeholder="Brief description of the expense">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Detailed Description *</label>
                <textarea name="description" id="description" required class="form-textarea" rows="3"
                          placeholder="Provide detailed description of the expense, including business purpose..."><?= htmlspecialchars($expense['description'] ?? '') ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="amount" class="form-label">Amount *</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #666666; font-weight: 500;">$</span>
                        <input type="number" name="amount" id="amount" step="0.01" min="0.01" required class="form-input" 
                               style="padding-left: 24px;" value="<?= $expense['amount'] ?>" placeholder="0.00">
                    </div>
                </div>

                <div class="form-group">
                    <label for="project_id" class="form-label">Related Project</label>
                    <select name="project_id" id="project_id" class="form-select">
                        <option value="">No specific project</option>
                        <?php if (isset($projects) && !empty($projects)): ?>
                            <?php foreach ($projects as $project): ?>
                                <option value="<?= $project['id'] ?>" <?= $expense['project_id'] == $project['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($project['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>

        <?php if ($user['role_name'] === 'admin'): ?>
        <!-- Admin Status Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Status & Admin Controls
            </h3>
            
            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="pending" <?= $expense['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= $expense['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= $expense['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
        </div>
        <?php endif; ?>

        <!-- Receipt Upload Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Receipt Upload
            </h3>
            
            <?php if (!empty($expense['receipt_image'])): ?>
            <div style="margin-bottom: 20px;">
                <label class="form-label">Current Receipt</label>
                <div style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 6px;">
                    <img src="/<?= htmlspecialchars($expense['receipt_image']) ?>" 
                         alt="Current Receipt" 
                         style="max-width: 200px; height: auto; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <p style="color: #666666; font-size: 12px; margin-top: 10px;">Current receipt image</p>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="receipt_image" class="form-label">Upload New Receipt (Optional)</label>
                <div style="border: 2px dashed #cccccc; border-radius: 6px; padding: 20px; text-align: center; background: #f9f9f9;">
                    <input type="file" name="receipt_image" id="receipt_image" accept="image/*" class="form-input" 
                           style="margin-bottom: 10px;">
                    <p style="color: #666666; font-size: 12px; margin: 0;">
                        Upload a new receipt image to replace the current one (JPG, PNG, GIF - Max 5MB)
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/expenses/<?= $expense['id'] ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Expense</button>
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
                <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Expense</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Update your expense details and information.
                </p>
            </div>
        </div>
        
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="/expenses/<?= $expense['id'] ?>/update" method="POST" enctype="multipart/form-data">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Expense Date</label>
                            <input type="date" name="date" id="date" value="<?= $expense['expense_date'] ?>"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                            <input type="text" name="title" id="title" required
                                value="<?= htmlspecialchars($expense['title'] ?? '') ?>"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Expense title">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                            <textarea name="description" id="description" rows="3" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Detailed description of the expense"><?= htmlspecialchars($expense['description'] ?? '') ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="amount" id="amount" step="0.01" min="0" required
                                        value="<?= $expense['amount'] ?>"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                                <select name="category" id="category" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select category</option>
                                    <option value="development" <?= $expense['category'] === 'development' ? 'selected' : '' ?>>Development</option>
                                    <option value="marketing" <?= $expense['category'] === 'marketing' ? 'selected' : '' ?>>Marketing</option>
                                    <option value="infrastructure" <?= $expense['category'] === 'infrastructure' ? 'selected' : '' ?>>Infrastructure</option>
                                    <option value="tools" <?= $expense['category'] === 'tools' ? 'selected' : '' ?>>Tools & Software</option>
                                    <option value="travel" <?= $expense['category'] === 'travel' ? 'selected' : '' ?>>Travel</option>
                                    <option value="other" <?= $expense['category'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <?php if (isset($user) && $user['role_name'] === 'admin'): ?>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="pending" <?= $expense['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $expense['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= $expense['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($expense['receipt_image'])): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Current Receipt</label>
                            <div class="mt-1">
                                <img src="/<?= htmlspecialchars($expense['receipt_image']) ?>" 
                                     alt="Current Receipt" 
                                     class="h-32 w-auto rounded-lg shadow-md">
                            </div>
                        </div>
                        <?php endif; ?>

                        <div>
                            <label for="receipt_image" class="block text-sm font-medium text-gray-700">
                                <?= !empty($expense['receipt_image']) ? 'Upload New Receipt (Optional)' : 'Receipt Image' ?>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="receipt_image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="receipt_image" name="receipt_image" type="file" accept="image/*" class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                        <a href="/expenses/<?= $expense['id'] ?>" 
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Expense
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

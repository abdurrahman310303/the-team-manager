<?php
$title = 'Edit Lead - Team Manager';
$currentPage = 'leads';

// Check if user is admin and use admin layout
if (isset($user) && $user['role_name'] === 'admin') {
    ob_start();
?>

<div class="page-header">
    <h1 class="page-title">Edit Lead</h1>
    <p class="page-subtitle">Update lead information and track business opportunities</p>
</div>

<div class="form-container">
    <form action="/leads/<?= $lead['id'] ?>/update" method="POST">
        
        <!-- Company Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Company Information
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="company_name" class="form-label">Company Name *</label>
                    <input type="text" name="company_name" id="company_name" required class="form-input" 
                           value="<?= htmlspecialchars($lead['company_name']) ?>"
                           placeholder="Enter company name">
                </div>

                <div class="form-group">
                    <label for="contact_person" class="form-label">Contact Person *</label>
                    <input type="text" name="contact_person" id="contact_person" required class="form-input" 
                           value="<?= htmlspecialchars($lead['contact_person']) ?>"
                           placeholder="Enter contact person name">
                </div>
            </div>
        </div>

        <!-- Contact Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Contact Information
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" name="email" id="email" required class="form-input" 
                           value="<?= htmlspecialchars($lead['email']) ?>"
                           placeholder="contact@company.com">
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number *</label>
                    <input type="tel" name="phone" id="phone" required class="form-input" 
                           value="<?= htmlspecialchars($lead['phone']) ?>"
                           placeholder="+1 (555) 123-4567">
                </div>
            </div>
        </div>

        <!-- Lead Details Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Lead Details
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="source" class="form-label">Lead Source</label>
                    <select name="source" id="source" class="form-select">
                        <option value="">Select lead source</option>
                        <option value="website" <?= $lead['source'] === 'website' ? 'selected' : '' ?>>Website</option>
                        <option value="referral" <?= $lead['source'] === 'referral' ? 'selected' : '' ?>>Referral</option>
                        <option value="social_media" <?= $lead['source'] === 'social_media' ? 'selected' : '' ?>>Social Media</option>
                        <option value="cold_call" <?= $lead['source'] === 'cold_call' ? 'selected' : '' ?>>Cold Call</option>
                        <option value="advertisement" <?= $lead['source'] === 'advertisement' ? 'selected' : '' ?>>Advertisement</option>
                        <option value="other" <?= $lead['source'] === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Lead Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="new" <?= $lead['status'] === 'new' ? 'selected' : '' ?>>New</option>
                        <option value="contacted" <?= $lead['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                        <option value="qualified" <?= $lead['status'] === 'qualified' ? 'selected' : '' ?>>Qualified</option>
                        <option value="proposal_sent" <?= $lead['status'] === 'proposal_sent' ? 'selected' : '' ?>>Proposal Sent</option>
                        <option value="negotiating" <?= $lead['status'] === 'negotiating' ? 'selected' : '' ?>>Negotiating</option>
                        <option value="closed_won" <?= $lead['status'] === 'closed_won' ? 'selected' : '' ?>>Closed Won</option>
                        <option value="closed_lost" <?= $lead['status'] === 'closed_lost' ? 'selected' : '' ?>>Closed Lost</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="estimated_value" class="form-label">Estimated Value</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #666666; font-weight: 500;">$</span>
                        <input type="number" name="estimated_value" id="estimated_value" step="0.01" min="0" class="form-input" 
                               style="padding-left: 24px;" 
                               value="<?= $lead['estimated_value'] ?>"
                               placeholder="0.00">
                    </div>
                </div>

                <div class="form-group">
                    <label for="assigned_to" class="form-label">Assign To</label>
                    <select name="assigned_to" id="assigned_to" class="form-select">
                        <option value="">Unassigned</option>
                        <?php if (isset($salespeople) && !empty($salespeople)): ?>
                            <?php foreach ($salespeople as $person): ?>
                                <option value="<?= $person['id'] ?>" <?= $lead['assigned_to'] == $person['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($person['name']) ?>
                                    <?= $person['role_display_name'] ? ' (' . htmlspecialchars($person['role_display_name']) . ')' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Additional Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Additional Information
            </h3>
            
            <div class="form-group">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-textarea" rows="4"
                          placeholder="Add any additional notes about the lead, requirements, or conversation details..."><?= htmlspecialchars($lead['notes'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Actions -->
        <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #e5e5e5;">
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="/leads/<?= $lead['id'] ?>" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Update Lead
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
                <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Lead</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Update the lead information and track business opportunities.
                </p>
            </div>
        </div>
        
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="/leads/<?= $lead['id'] ?>/update" method="POST">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name *</label>
                                <input type="text" name="company_name" id="company_name" required
                                    value="<?= htmlspecialchars($lead['company_name']) ?>"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Company name">
                            </div>

                            <div>
                                <label for="contact_person" class="block text-sm font-medium text-gray-700">Contact Name *</label>
                                <input type="text" name="contact_person" id="contact_person" required
                                    value="<?= htmlspecialchars($lead['contact_person']) ?>"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Contact person name">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                <input type="email" name="email" id="email" required
                                    value="<?= htmlspecialchars($lead['email']) ?>"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="contact@company.com">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone *</label>
                                <input type="tel" name="phone" id="phone" required
                                    value="<?= htmlspecialchars($lead['phone']) ?>"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="+1 (555) 123-4567">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="source" class="block text-sm font-medium text-gray-700">Lead Source</label>
                                <select name="source" id="source"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select source</option>
                                    <option value="website" <?= $lead['source'] === 'website' ? 'selected' : '' ?>>Website</option>
                                    <option value="referral" <?= $lead['source'] === 'referral' ? 'selected' : '' ?>>Referral</option>
                                    <option value="social_media" <?= $lead['source'] === 'social_media' ? 'selected' : '' ?>>Social Media</option>
                                    <option value="cold_call" <?= $lead['source'] === 'cold_call' ? 'selected' : '' ?>>Cold Call</option>
                                    <option value="advertisement" <?= $lead['source'] === 'advertisement' ? 'selected' : '' ?>>Advertisement</option>
                                    <option value="other" <?= $lead['source'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="new" <?= $lead['status'] === 'new' ? 'selected' : '' ?>>New</option>
                                    <option value="contacted" <?= $lead['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                                    <option value="qualified" <?= $lead['status'] === 'qualified' ? 'selected' : '' ?>>Qualified</option>
                                    <option value="proposal_sent" <?= $lead['status'] === 'proposal_sent' ? 'selected' : '' ?>>Proposal Sent</option>
                                    <option value="negotiating" <?= $lead['status'] === 'negotiating' ? 'selected' : '' ?>>Negotiating</option>
                                    <option value="closed_won" <?= $lead['status'] === 'closed_won' ? 'selected' : '' ?>>Closed Won</option>
                                    <option value="closed_lost" <?= $lead['status'] === 'closed_lost' ? 'selected' : '' ?>>Closed Lost</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="estimated_value" class="block text-sm font-medium text-gray-700">Estimated Value</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="estimated_value" id="estimated_value" step="0.01" min="0"
                                        value="<?= $lead['estimated_value'] ?>"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <div>
                                <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assign To</label>
                                <select name="assigned_to" id="assigned_to"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Unassigned</option>
                                    <?php foreach ($salespeople as $person): ?>
                                        <option value="<?= $person['id'] ?>" <?= $lead['assigned_to'] == $person['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($person['name']) ?><?= $person['role_display_name'] ? ' (' . htmlspecialchars($person['role_display_name']) . ')' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="4"
                                class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Additional notes about the lead..."><?= htmlspecialchars($lead['notes'] ?? '') ?></textarea>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                        <a href="/leads/<?= $lead['id'] ?>" 
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Lead
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

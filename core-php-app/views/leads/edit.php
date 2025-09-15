<?php
$title = 'Edit Lead - Team Manager';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Lead</h1>
        <p class="page-subtitle">Update lead information and track business opportunities</p>
    </div>
    <div class="page-actions">
        <a href="/leads/<?= $lead['id'] ?>" class="btn btn-secondary">← Back to Lead</a>
    </div>
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
                                    (<?= htmlspecialchars($person['role_display_name'] ?? $person['role_name'] ?? 'User') ?>)
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
        <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/leads/<?= $lead['id'] ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Lead</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

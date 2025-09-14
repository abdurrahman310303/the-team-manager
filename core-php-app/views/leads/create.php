<?php
$title = 'Create Lead - Team Manager';
ob_start();
?>

<div class="page-header">
    <h1 class="page-title">Create New Lead</h1>
    <p class="page-subtitle">Add a new sales lead to track potential business opportunities.</p>
</div>

<div class="form-container">
    <form action="/leads/store" method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="company_name" class="form-label">Company Name *</label>
                <input type="text" name="company_name" id="company_name" required class="form-input" placeholder="Company name">
            </div>

            <div class="form-group">
                <label for="contact_person" class="form-label">Contact Name *</label>
                <input type="text" name="contact_person" id="contact_person" required class="form-input" placeholder="Contact person name">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="email" class="form-label">Email *</label>
                <input type="email" name="email" id="email" required class="form-input" placeholder="contact@company.com">
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone *</label>
                <input type="tel" name="phone" id="phone" required class="form-input" placeholder="+1 (555) 123-4567">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="source" class="form-label">Lead Source</label>
                <select name="source" id="source" class="form-select">
                    <option value="">Select source</option>
                    <option value="website">Website</option>
                    <option value="referral">Referral</option>
                    <option value="social_media">Social Media</option>
                    <option value="cold_call">Cold Call</option>
                    <option value="email_campaign">Email Campaign</option>
                    <option value="trade_show">Trade Show</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="new">New</option>
                    <option value="contacted">Contacted</option>
                    <option value="qualified">Qualified</option>
                    <option value="proposal">Proposal</option>
                    <option value="won">Won</option>
                    <option value="lost">Lost</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="potential_value" class="form-label">Potential Value</label>
                <input type="number" name="potential_value" id="potential_value" step="0.01" min="0" class="form-input" placeholder="0.00">
            </div>

            <div class="form-group">
                <label for="assigned_to" class="form-label">Assign To</label>
                <select name="assigned_to" id="assigned_to" class="form-select">
                    <option value="">Unassigned</option>
                    <?php foreach ($salespeople as $person): ?>
                        <option value="<?= $person['id'] ?>"><?= htmlspecialchars($person['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" rows="4" class="form-textarea" placeholder="Additional notes about the lead..."></textarea>
        </div>
        
        <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/leads" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Lead</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

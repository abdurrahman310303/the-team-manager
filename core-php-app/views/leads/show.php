<?php
$title = 'Lead Details - Team Manager';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= htmlspecialchars($lead['company_name']) ?></h1>
        <p class="page-subtitle">Lead information and contact details</p>
    </div>
    <div class="page-actions">
        <a href="/leads" class="btn btn-secondary">← Back to Leads</a>
        <?php if (Auth::canEditLead($lead)): ?>
            <a href="/leads/<?= $lead['id'] ?>/edit" class="btn btn-primary" style="margin-left: 10px;">Edit Lead</a>
        <?php endif; ?>
    </div>
</div>

<div class="form-container">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <div>
            <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #000;">Contact Information</h3>
            
            <div class="form-group">
                <label class="form-label">Contact Name</label>
                <div style="padding: 12px; background: #f8f9fa; border: 1px solid #e5e5e5; border-radius: 4px; color: #000;">
                    <?= htmlspecialchars($lead['contact_person']) ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <div style="padding: 12px; background: #f8f9fa; border: 1px solid #e5e5e5; border-radius: 4px;">
                    <a href="mailto:<?= htmlspecialchars($lead['email']) ?>" style="color: #000; text-decoration: none;">
                        <?= htmlspecialchars($lead['email']) ?>
                    </a>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Phone</label>
                <div style="padding: 12px; background: #f8f9fa; border: 1px solid #e5e5e5; border-radius: 4px;">
                    <a href="tel:<?= htmlspecialchars($lead['phone']) ?>" style="color: #000; text-decoration: none;">
                        <?= htmlspecialchars($lead['phone'] ?: 'Not provided') ?>
                    </a>
                </div>
            </div>

            <?php if ($lead['source']): ?>
            <div class="form-group">
                <label class="form-label">Lead Source</label>
                <div style="padding: 12px; background: #f8f9fa; border: 1px solid #e5e5e5; border-radius: 4px; color: #000;">
                    <?= ucwords(str_replace('_', ' ', $lead['source'])) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div>
            <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #000;">Lead Details</h3>
            
            <div class="form-group">
                <label class="form-label">Status</label>
                <div style="padding: 12px; background: #f8f9fa; border: 1px solid #e5e5e5; border-radius: 4px;">
                    <span class="badge <?php 
                        switch($lead['status']) {
                            case 'new': echo 'badge-info'; break;
                            case 'contacted': echo 'badge-pending'; break;
                            case 'qualified': echo 'badge-success'; break;
                            case 'proposal_sent': echo 'badge-info'; break;
                            case 'negotiating': echo 'badge-pending'; break;
                            case 'closed_won': echo 'badge-approved'; break;
                            case 'closed_lost': echo 'badge-danger'; break;
                            default: echo 'badge-pending'; break;
                        }
                    ?>">
                        <?= ucwords(str_replace('_', ' ', $lead['status'])) ?>
                    </span>
                </div>
            </div>

            <?php if ($lead['estimated_value']): ?>
            <div class="form-group">
                <label class="form-label">Potential Value</label>
                <div style="padding: 12px; background: #f8f9fa; border: 1px solid #e5e5e5; border-radius: 4px; color: #000; font-weight: 600;">
                    $<?= number_format($lead['estimated_value'], 2) ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">Assigned To</label>
                <div style="padding: 12px; background: #f8f9fa; border: 1px solid #e5e5e5; border-radius: 4px; color: #000;">
                    <?= htmlspecialchars($lead['assigned_name'] ?? 'Unassigned') ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Created</label>
                <div style="padding: 12px; background: #f8f9fa; border: 1px solid #e5e5e5; border-radius: 4px; color: #000;">
                    <?= date('M j, Y g:i A', strtotime($lead['created_at'])) ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($lead['notes']): ?>
    <div class="form-group" style="margin-top: 30px;">
        <label class="form-label">Notes</label>
        <div style="padding: 12px; background: #f8f9fa; border: 1px solid #e5e5e5; border-radius: 4px; color: #000; white-space: pre-wrap; min-height: 100px;">
            <?= htmlspecialchars($lead['notes']) ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

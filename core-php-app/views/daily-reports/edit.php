<?php
$title = 'Edit Daily Report - Team Manager';
$currentPage = 'daily-reports';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Daily Report</h1>
        <p class="page-subtitle">Update daily work activities, challenges, and plans</p>
    </div>
    <div class="page-actions">
        <a href="/daily-reports/<?= $report['id'] ?>" class="btn btn-secondary">← Back to Report</a>
    </div>
</div>

<div class="form-container">
    <form action="/daily-reports/<?= $report['id'] ?>/update" method="POST">
        
        <!-- Basic Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Report Information
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="report_date" class="form-label">Date *</label>
                    <input type="date" id="report_date" name="report_date" value="<?= $report['report_date'] ?>" required class="form-input">
                </div>
                <div class="form-group">
                    <label for="hours_worked" class="form-label">Hours Worked *</label>
                    <input type="number" id="hours_worked" name="hours_worked" step="0.5" min="0" max="24" 
                           value="<?= $report['hours_worked'] ?>" placeholder="8.0" required class="form-input">
                </div>
                <div class="form-group">
                    <label for="report_type" class="form-label">Report Type *</label>
                    <select id="report_type" name="report_type" required class="form-input" disabled>
                        <option value="general" <?= $report['report_type'] === 'general' ? 'selected' : '' ?>>General</option>
                        <option value="developer" <?= $report['report_type'] === 'developer' ? 'selected' : '' ?>>Developer</option>
                        <option value="bd" <?= $report['report_type'] === 'bd' ? 'selected' : '' ?>>Business Development</option>
                    </select>
                    <input type="hidden" name="report_type" value="<?= $report['report_type'] ?>">
                    <small style="color: #666666; font-size: 12px;">Report type cannot be changed after creation</small>
                </div>
            </div>
        </div>

        <!-- Work Details Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Work Details
            </h3>
            
            <div class="form-group">
                <label for="work_completed" class="form-label">Work Completed Today *</label>
                <textarea id="work_completed" name="work_completed" rows="5" required class="form-textarea"
                          placeholder="Describe what you accomplished today..."><?= htmlspecialchars($report['work_completed'] ?? '') ?></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="challenges_faced" class="form-label">Challenges Faced</label>
                    <textarea id="challenges_faced" name="challenges_faced" rows="4" class="form-textarea"
                              placeholder="Any challenges or obstacles you encountered..."><?= htmlspecialchars($report['challenges_faced'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="next_plans" class="form-label">Next Plans</label>
                    <textarea id="next_plans" name="next_plans" rows="4" class="form-textarea"
                              placeholder="What do you plan to work on next..."><?= htmlspecialchars($report['next_plans'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <?php if ($report['report_type'] === 'bd'): ?>
        <!-- Business Development Metrics Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Business Development Metrics
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="leads_generated" class="form-label">Leads Generated</label>
                    <input type="number" id="leads_generated" name="leads_generated" min="0" 
                           value="<?= $report['leads_generated'] ?? 0 ?>" placeholder="0" class="form-input">
                </div>
                <div class="form-group">
                    <label for="proposals_submitted" class="form-label">Proposals Submitted</label>
                    <input type="number" id="proposals_submitted" name="proposals_submitted" min="0" 
                           value="<?= $report['proposals_submitted'] ?? 0 ?>" placeholder="0" class="form-input">
                </div>
                <div class="form-group">
                    <label for="projects_locked" class="form-label">Projects Locked</label>
                    <input type="number" id="projects_locked" name="projects_locked" min="0" 
                           value="<?= $report['projects_locked'] ?? 0 ?>" placeholder="0" class="form-input">
                </div>
                <div class="form-group">
                    <label for="revenue_generated" class="form-label">Revenue Generated ($)</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #666666; font-weight: 500;">$</span>
                        <input type="number" id="revenue_generated" name="revenue_generated" step="0.01" min="0" 
                               value="<?= $report['revenue_generated'] ?? 0 ?>" placeholder="0.00" class="form-input" 
                               style="padding-left: 24px;">
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Additional Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Additional Information
            </h3>
            
            <div class="form-group">
                <label for="notes" class="form-label">Additional Notes</label>
                <textarea id="notes" name="notes" rows="4" class="form-textarea"
                          placeholder="Any additional notes or comments..."><?= htmlspecialchars($report['notes'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/daily-reports/<?= $report['id'] ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Report</button>
        </div>
    </form>
</div>
?>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

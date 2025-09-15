<?php
$title = 'Create Daily Report - Team Manager';
$currentPage = 'daily-reports';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Create Daily Report</h1>
        <p class="page-subtitle">Record your daily work activities, challenges, and plans</p>
    </div>
    <div class="page-actions">
        <a href="/daily-reports" class="btn btn-secondary">
            ← Back to Reports
        </a>
    </div>
</div>

<div class="form-container">
    <form action="/daily-reports/store" method="POST">
        <!-- Basic Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Report Information
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="report_date" class="form-label">Date *</label>
                    <input type="date" name="report_date" id="report_date" value="<?= date('Y-m-d') ?>" required class="form-input">
                </div>
                <div class="form-group">
                    <label for="hours_worked" class="form-label">Hours Worked *</label>
                    <input type="number" name="hours_worked" id="hours_worked" step="0.5" min="0" max="24" required class="form-input" placeholder="8.0">
                </div>
                <div class="form-group">
                    <label for="report_type" class="form-label">Report Type *</label>
                    <select name="report_type" id="report_type" required class="form-input">
                        <option value="">Select Type</option>
                        <option value="general">General</option>
                        <?php if (Auth::hasAnyRole(['developer', 'admin'])): ?>
                        <option value="developer">Developer</option>
                        <?php endif; ?>
                        <?php if (Auth::hasAnyRole(['bd', 'admin'])): ?>
                        <option value="bd">Business Development</option>
                        <?php endif; ?>
                    </select>
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
                <textarea name="work_completed" id="work_completed" rows="5" required class="form-textarea" placeholder="Describe what you accomplished today..."></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="challenges_faced" class="form-label">Challenges Faced</label>
                    <textarea name="challenges_faced" id="challenges_faced" rows="4" class="form-textarea" placeholder="Any challenges or obstacles you encountered..."></textarea>
                </div>
                <div class="form-group">
                    <label for="next_plans" class="form-label">Next Plans</label>
                    <textarea name="next_plans" id="next_plans" rows="4" class="form-textarea" placeholder="What do you plan to work on next..."></textarea>
                </div>
            </div>
        </div>

        <!-- Business Development Metrics Section (shown when BD type is selected) -->
        <div id="bd-metrics" style="margin-bottom: 30px; display: none;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Business Development Metrics
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="leads_generated" class="form-label">Leads Generated</label>
                    <input type="number" name="leads_generated" id="leads_generated" min="0" value="0" class="form-input">
                </div>
                <div class="form-group">
                    <label for="proposals_submitted" class="form-label">Proposals Submitted</label>
                    <input type="number" name="proposals_submitted" id="proposals_submitted" min="0" value="0" class="form-input">
                </div>
                <div class="form-group">
                    <label for="projects_locked" class="form-label">Projects Locked</label>
                    <input type="number" name="projects_locked" id="projects_locked" min="0" value="0" class="form-input">
                </div>
                <div class="form-group">
                    <label for="revenue_generated" class="form-label">Revenue Generated ($)</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #666666; font-weight: 500;">$</span>
                        <input type="number" name="revenue_generated" id="revenue_generated" step="0.01" min="0" value="0" class="form-input" style="padding-left: 24px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Additional Information
            </h3>
            
            <div class="form-group">
                <label for="notes" class="form-label">Additional Notes</label>
                <textarea name="notes" id="notes" rows="4" class="form-textarea" placeholder="Any additional notes or comments..."></textarea>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/daily-reports" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Report</button>
        </div>
    </form>
</div>

<script>
document.getElementById('report_type').addEventListener('change', function() {
    const bdMetrics = document.getElementById('bd-metrics');
    if (this.value === 'bd') {
        bdMetrics.style.display = 'block';
    } else {
        bdMetrics.style.display = 'none';
    }
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

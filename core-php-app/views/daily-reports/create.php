<?php
$title = 'Create Daily Report - Team Manager';
ob_start();
?>

<div class="page-header">
    <h1 class="page-title">Daily Work Report</h1>
    <p class="page-subtitle">Record your daily work activities, challenges, and plans for tomorrow.</p>
</div>

<div class="form-container">
    <form action="/daily-reports/store" method="POST">
        <div class="form-group">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" value="<?= date('Y-m-d') ?>" class="form-input">
        </div>

        <div class="form-group">
            <label for="work_done" class="form-label">Work Completed Today *</label>
            <textarea name="work_done" id="work_done" rows="4" required class="form-textarea" placeholder="Describe what you accomplished today..."></textarea>
        </div>

        <div class="form-group">
            <label for="hours_worked" class="form-label">Hours Worked *</label>
            <input type="number" name="hours_worked" id="hours_worked" step="0.5" min="0" max="24" required class="form-input" placeholder="8.0">
        </div>

        <div class="form-group">
            <label for="challenges" class="form-label">Challenges Faced</label>
            <textarea name="challenges" id="challenges" rows="3" class="form-textarea" placeholder="Any challenges or obstacles you encountered..."></textarea>
        </div>

        <div class="form-group">
            <label for="tomorrow_plan" class="form-label">Tomorrow's Plan</label>
            <textarea name="tomorrow_plan" id="tomorrow_plan" rows="3" class="form-textarea" placeholder="What do you plan to work on tomorrow..."></textarea>
        </div>
        
        <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/daily-reports" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit Report</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

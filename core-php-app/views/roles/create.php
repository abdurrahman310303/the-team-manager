<?php
$title = 'Create Role - Team Manager';
$currentPage = 'roles';
ob_start();
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1 class="page-title">Create New Role</h1>
            <p class="page-subtitle">Add a new user role to the system</p>
        </div>
        <div>
            <a href="/roles" class="btn btn-secondary">
                <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Roles
            </a>
        </div>
    </div>
</div>

<div class="form-container">
    <form action="/roles/store" method="POST">
        
        <!-- Basic Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Role Information
            </h3>
            
            <div class="form-group">
                <label for="name" class="form-label">Role Name *</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="e.g., admin, developer, bd" required>
                <small style="color: #666666; font-size: 12px;">Lowercase, no spaces. Used internally in the system.</small>
            </div>
            
            <div class="form-group">
                <label for="display_name" class="form-label">Display Name *</label>
                <input type="text" id="display_name" name="display_name" class="form-input" placeholder="e.g., Administrator, Developer, Business Development" required>
                <small style="color: #666666; font-size: 12px;">Human-readable name shown to users.</small>
            </div>
            
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" rows="4" class="form-textarea" placeholder="Describe the role's responsibilities and permissions..."></textarea>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/roles" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Role</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

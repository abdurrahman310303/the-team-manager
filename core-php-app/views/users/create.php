<?php
$title = 'Create User - Team Manager';
ob_start();
?>

<div class="page-header">
    <h1 class="page-title">Create New User</h1>
    <p class="page-subtitle">Add a new user to the system with appropriate role permissions.</p>
</div>

<div class="form-container">
    <form action="/users/store" method="POST">
        <div class="form-group">
            <label for="name" class="form-label">Full Name *</label>
            <input type="text" name="name" id="name" required class="form-input" placeholder="John Doe">
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address *</label>
            <input type="email" name="email" id="email" required class="form-input" placeholder="john@example.com">
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password *</label>
            <input type="password" name="password" id="password" required class="form-input" placeholder="Enter secure password">
        </div>

        <div class="form-group">
            <label for="role_id" class="form-label">Role *</label>
            <select name="role_id" id="role_id" required class="form-select">
                <option value="">Select a role</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['display_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/users" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create User</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>

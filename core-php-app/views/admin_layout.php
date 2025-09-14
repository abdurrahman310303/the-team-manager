<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Team Manager' ?></title>
    <style>
        /* Minimal Black & White Admin Theme */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: #ffffff;
            color: #000000;
            line-height: 1.5;
        }
        
        /* Navigation */
        .admin-nav {
            background: #1a1a1a;
            border-bottom: 1px solid #333;
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        
        .nav-brand {
            color: #ffffff;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            padding: 16px 0;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .nav-item {
            margin: 0;
        }
        
        .nav-link {
            color: #ffffff;
            text-decoration: none;
            padding: 16px 20px;
            display: block;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        
        .nav-link:hover {
            background: #2a2a2a;
        }
        
        .nav-link.active {
            background: #2a2a2a;
            border-bottom: 2px solid #ffffff;
        }
        
        .nav-user {
            position: relative;
        }
        
        .user-dropdown {
            color: #ffffff;
            background: none;
            border: 1px solid #404040;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .user-dropdown:hover {
            background: #2a2a2a;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            min-width: 150px;
            z-index: 1001;
            margin-top: 2px;
        }
        
        .dropdown-content.show {
            display: block !important;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #1a1a1a;
                flex-direction: column;
                border-top: 1px solid #333;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            }
            
            .nav-menu.mobile-open {
                display: flex;
            }
            
            .nav-item {
                width: 100%;
            }
            
            .nav-link {
                border-bottom: 1px solid #333;
                padding: 15px 20px;
            }
            
            .nav-link.active {
                border-bottom: 1px solid #333;
                background: #2a2a2a;
            }
            
            .mobile-menu-btn {
                display: block;
                position: absolute;
                right: 100px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #ffffff;
                cursor: pointer;
                padding: 8px;
                border-radius: 4px;
                transition: background-color 0.2s;
            }
            
            .mobile-menu-btn:hover {
                background: #2a2a2a;
            }
            
            .main-container {
                margin: 0 auto;
                padding: 140px 20px 30px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .page-actions {
                width: 100%;
            }
            
            .admin-table {
                font-size: 12px;
            }
            
            .admin-table th,
            .admin-table td {
                padding: 12px 8px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
            
            .btn-sm {
                padding: 6px 12px;
                font-size: 12px;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            .user-info {
                min-width: 150px;
            }
            
            .date-cell {
                font-size: 11px;
            }
        }
        
        @media (max-width: 480px) {
            .page-title {
                font-size: 24px;
            }
            
            .admin-table th,
            .admin-table td {
                padding: 8px 6px;
            }
            
            .action-buttons .btn {
                padding: 4px 8px;
                font-size: 11px;
            }
        }
        
        /* Mobile menu button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: #ffffff;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        
        .mobile-menu-btn:hover {
            background: #2a2a2a;
        }
        
        /* Main Container */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 40px 40px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        /* Override any potential top padding/margin issues from content */
        .main-container > *:first-child,
        .main-container .px-4,
        .main-container .py-6,
        .main-container [class*="px-"],
        .main-container [class*="py-"] {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Ensure any content with top spacing accounts for navbar */
        .main-container > div[class*="px-"],
        .main-container > div[class*="py-"] {
            margin-top: 20px !important;
        }
        
        /* Handle Tailwind containers that might interfere */
        .main-container .max-w-4xl,
        .main-container .max-w-6xl,
        .main-container .max-w-7xl {
            max-width: 100% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        
        /* Ensure form containers and cards use full width within centered layout */
        .form-container,
        .card,
        .table-container {
            width: 100%;
            margin-left: 0;
            margin-right: 0;
        }
        
        .dropdown-link {
            color: #000000;
            text-decoration: none;
            padding: 12px 16px;
            display: block;
            font-size: 14px;
            border-bottom: 1px solid #f5f5f5;
        }
        
        .dropdown-link:hover {
            background: #f5f5f5;
        }
        
        .dropdown-link:last-child {
            border-bottom: none;
        }
        
        /* Page Header */
        .page-header {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }
        
        .page-subtitle {
            font-size: 16px;
            color: #666666;
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid #1a1a1a;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            background: #ffffff;
            color: #1a1a1a;
        }
        
        .btn:hover {
            background: #1a1a1a;
            color: #ffffff;
        }
        
        .btn-primary {
            background: #1a1a1a;
            color: #ffffff;
        }
        
        .btn-primary:hover {
            background: #2a2a2a;
        }
        
        .btn-secondary {
            background: #ffffff;
            color: #666666;
            border-color: #cccccc;
        }
        
        .btn-secondary:hover {
            background: #f5f5f5;
            color: #000000;
        }
        
        .btn-success {
            background: #ffffff;
            color: #059669;
            border-color: #059669;
        }
        
        .btn-success:hover {
            background: #059669;
            color: #ffffff;
        }
        
        .btn-danger {
            background: #ffffff;
            color: #1a1a1a;
            border-color: #1a1a1a;
        }
        
        .btn-danger:hover {
            background: #1a1a1a;
            color: #ffffff;
        }
        
        /* Tables */
        .table-container {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .admin-table th {
            background: #f9f9f9;
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            color: #1a1a1a;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .admin-table td {
            padding: 16px;
            border-bottom: 1px solid #f5f5f5;
            color: #1a1a1a;
            font-size: 14px;
        }
        
        .admin-table tr:hover {
            background: #f9f9f9;
        }
        
        /* Forms */
        .form-container {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #1a1a1a;
            font-size: 14px;
        }
        
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 14px;
            color: #1a1a1a;
            background: #ffffff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #1a1a1a;
            box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
        }
        
        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        /* Enhanced form styling */
        .form-input:hover:not(:focus),
        .form-select:hover:not(:focus),
        .form-textarea:hover:not(:focus) {
            border-color: #999999;
        }
        
        /* Checkbox styling */
        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #1a1a1a;
        }
        
        /* Section headers */
        .form-section-header {
            color: #1a1a1a;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e5e5;
        }
        
        /* Cards */
        .card {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 12px;
        }
        
        .card-content {
            color: #666666;
            font-size: 14px;
            line-height: 1.6;
        }
        
        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: 500;
            border-radius: 3px;
            text-transform: uppercase;
        }
        
        .badge-pending {
            background: #f5f5f5;
            color: #666666;
            border: 1px solid #cccccc;
        }
        
        .badge-approved {
            background: #1a1a1a;
            color: #ffffff;
        }
        
        .badge-rejected {
            background: #ffffff;
            color: #1a1a1a;
            border: 1px solid #1a1a1a;
        }
        
        /* Flash Messages */
        .flash-message {
            padding: 16px 20px;
            margin: 20px auto;
            max-width: 1200px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .flash-success {
            background: #f9f9f9;
            color: #000000;
            border: 1px solid #e5e5e5;
        }
        
        .flash-error {
            background: #ffffff;
            color: #1a1a1a;
            border: 2px solid #1a1a1a;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
            
            .main-container {
                padding: 140px 20px 30px !important;
                margin: 0 auto;
            }
            
            /* Override content spacing on mobile */
            .main-container > *:first-child,
            .main-container .px-4,
            .main-container .py-6,
            .main-container [class*="px-"],
            .main-container [class*="py-"] {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }
            
            .main-container > div[class*="px-"],
            .main-container > div[class*="py-"] {
                margin-top: 15px !important;
            }
            
            .nav-container {
                flex-direction: column;
                padding: 10px 20px;
            }
            
            .nav-menu {
                margin-top: 10px;
                flex-wrap: wrap;
            }
            
            .nav-link {
                padding: 12px 16px;
            }
            
            .admin-table {
                font-size: 12px;
            }
            
            .admin-table th,
            .admin-table td {
                padding: 8px;
            }
        }
        
        @media (max-width: 480px) {
            .main-container {
                padding: 140px 15px 30px !important;
            }
            
            .page-title {
                font-size: 24px;
            }
            
            .admin-table th,
            .admin-table td {
                padding: 8px 6px;
            }
            
            .action-buttons .btn {
                padding: 4px 8px;
                font-size: 11px;
            }
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            padding: 24px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: 600;
            color: #1a1a1a;
            display: block;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666666;
            margin-top: 8px;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-new { background: #f0f9ff; color: #0369a1; }
        .status-contacted { background: #fefce8; color: #ca8a04; }
        .status-qualified { background: #f0fdf4; color: #16a34a; }
        .status-proposal { background: #faf5ff; color: #9333ea; }
        .status-won { background: #f0fdf4; color: #16a34a; }
        .status-lost { background: #fef2f2; color: #dc2626; }
        .status-active { background: #f0fdf4; color: #16a34a; }
        .status-pending { background: #fefce8; color: #ca8a04; }
        .status-completed { background: #f0fdf4; color: #16a34a; }
        .status-cancelled { background: #fef2f2; color: #dc2626; }
        .status-planning { background: #f0f9ff; color: #0369a1; }
        .status-in_progress { background: #fefce8; color: #ca8a04; }
        .status-on_hold { background: #f3f4f6; color: #4b5563; }
        .status-approved { background: #f0fdf4; color: #16a34a; }
        .status-rejected { background: #fef2f2; color: #dc2626; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="admin-nav">
        <div class="nav-container">
            <a href="/dashboard" class="nav-brand">Team Manager</a>
            
            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn" onclick="toggleSidebar()">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
            </button>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                </li>
                
                <?php if (Auth::hasRole('admin')): ?>
                <li class="nav-item">
                    <a href="/users" class="nav-link <?= ($currentPage ?? '') === 'users' ? 'active' : '' ?>">Users</a>
                </li>
                <li class="nav-item">
                    <a href="/roles" class="nav-link <?= ($currentPage ?? '') === 'roles' ? 'active' : '' ?>">Roles</a>
                </li>
                <?php endif; ?>
                
                <?php if (Auth::hasAnyRole(['admin', 'investor'])): ?>
                <li class="nav-item">
                    <a href="/projects" class="nav-link <?= ($currentPage ?? '') === 'projects' ? 'active' : '' ?>">Projects</a>
                </li>
                <?php endif; ?>
                
                <?php if (Auth::hasAnyRole(['admin', 'bd', 'investor'])): ?>
                <li class="nav-item">
                    <a href="/leads" class="nav-link <?= ($currentPage ?? '') === 'leads' ? 'active' : '' ?>">Leads</a>
                </li>
                <?php endif; ?>
                
                <?php if (Auth::hasAnyRole(['admin', 'bd', 'developer'])): ?>
                <li class="nav-item">
                    <a href="/daily-reports" class="nav-link <?= ($currentPage ?? '') === 'daily-reports' ? 'active' : '' ?>">Reports</a>
                </li>
                <?php endif; ?>
                
                <?php if (Auth::hasAnyRole(['admin', 'bd', 'developer'])): ?>
                <li class="nav-item">
                    <a href="/expenses" class="nav-link <?= ($currentPage ?? '') === 'expenses' ? 'active' : '' ?>">Expenses</a>
                </li>
                <?php endif; ?>
                
                <?php if (Auth::hasAnyRole(['admin', 'investor'])): ?>
                <li class="nav-item">
                    <a href="/payments" class="nav-link <?= ($currentPage ?? '') === 'payments' ? 'active' : '' ?>">Payments</a>
                </li>
                <?php endif; ?>
            </ul>
            
            <div class="nav-user">
                <button class="user-dropdown" onclick="toggleDropdown()">
                    <?= htmlspecialchars(Auth::user()['name'] ?? 'User') ?> 
                    <small>(<?= ucfirst(Auth::user()['role_name'] ?? 'user') ?>)</small> ▼
                </button>
                <div class="dropdown-content" id="userDropdown">
                    <a href="/users/<?= Auth::user()['id'] ?>" class="dropdown-link">Profile</a>
                    <?php if (Auth::hasRole('admin')): ?>
                    <a href="/roles" class="dropdown-link">Manage Roles</a>
                    <?php endif; ?>
                    <a href="/logout" class="dropdown-link">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php $successMessage = Session::getFlash('success'); ?>
    <?php if ($successMessage): ?>
    <div class="flash-message flash-success">
        ✓ <?= htmlspecialchars($successMessage) ?>
    </div>
    <?php endif; ?>

    <?php $errorMessage = Session::getFlash('error'); ?>
    <?php if ($errorMessage): ?>
    <div class="flash-message flash-error">
        ⚠ <?= htmlspecialchars($errorMessage) ?>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main-container">
        <?= $content ?>
    </main>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }
        
        function toggleSidebar() {
            const navMenu = document.querySelector('.nav-menu');
            if (navMenu) {
                navMenu.classList.toggle('mobile-open');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = event.target.closest('.user-dropdown');
            
            if (!button && dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const navMenu = document.querySelector('.nav-menu');
            const menuBtn = event.target.closest('.mobile-menu-btn');
            const navItem = event.target.closest('.nav-menu');
            
            if (!menuBtn && !navItem && navMenu && navMenu.classList.contains('mobile-open')) {
                navMenu.classList.remove('mobile-open');
            }
        });
    </script>

</body>
</html>

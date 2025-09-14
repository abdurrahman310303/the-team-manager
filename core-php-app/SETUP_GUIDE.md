# Team Manager - Core PHP Application

## Overview
A complete team management system built with core PHP featuring role-based access control and a clean black & white admin interface.

## Features Completed

### Role-Based Access Control
- **Admin**: Full system access - can manage all users, projects, leads, reports, expenses, and payments
- **Developer**: Can create daily reports, submit expenses, view assigned projects
- **Business Development (BD)**: Can create and manage leads, submit daily reports and expenses
- **Investor**: Can manage payments, view projects and leads (read-only)

### Core Modules
- ✅ **User Management** (Admin only)
- ✅ **Role Management** (Admin only)
- ✅ **Project Management** (Admin creates/edits, Investors can view)
- ✅ **Lead Management** (BD and Admin create/edit, Investors can view)
- ✅ **Daily Reports** (All except Investors)
- ✅ **Expense Management** (All except Investors submit, Admin approves)
- ✅ **Payment Management** (Investors and Admin)

### Authentication System
- Secure login/logout
- Session management
- Role-based middleware
- Password hashing

### Theme
- Clean black & white minimalist design
- Responsive layout
- Role-based navigation
- Professional admin interface

## Setup Instructions

### 1. Database Setup
```bash
# Import the database schema
mysql -u your_username -p < database.sql
```

### 2. Configure Database Connection
Edit `config/database.php`:
```php
return [
    'host' => 'localhost',
    'dbname' => 'team_manager_core',
    'username' => 'your_username',
    'password' => 'your_password'
];
```

### 3. Start the Application
```bash
# Navigate to the project directory
cd core-php-app

# Start PHP built-in server
php -S localhost:8000 router.php
```

### 4. Default Login
- **Email**: admin@teammanager.com
- **Password**: password
- **Role**: Administrator

## File Structure
```
core-php-app/
├── config/
│   └── database.php          # Database configuration
├── core/
│   ├── Auth.php             # Authentication middleware
│   ├── Database.php         # Database connection
│   ├── Router.php           # URL routing
│   └── Session.php          # Session management
├── controllers/
│   ├── AuthController.php   # Login/logout
│   ├── DashboardController.php
│   ├── ProjectController.php
│   ├── LeadController.php
│   ├── DailyReportController.php
│   ├── ExpenseController.php
│   ├── PaymentController.php
│   ├── UserController.php
│   └── RoleController.php
├── models/
│   ├── User.php
│   ├── Project.php
│   ├── Lead.php
│   ├── DailyReport.php
│   ├── Expense.php
│   └── Payment.php
├── views/
│   ├── admin_layout.php     # Main layout template
│   ├── auth/               # Login/register views
│   ├── dashboard/          # Dashboard views
│   ├── users/              # User management views
│   ├── projects/           # Project views
│   ├── leads/              # Lead management views
│   ├── daily-reports/      # Report views
│   ├── expenses/           # Expense views
│   └── payments/           # Payment views
├── uploads/                # File uploads directory
├── index.php              # Main entry point
├── router.php             # Router for built-in server
└── database.sql           # Database schema
```

## Role Permissions Summary

| Feature | Admin | Developer | BD | Investor |
|---------|-------|-----------|----| ---------|
| Users | Full CRUD | View own | View own | View own |
| Roles | Full CRUD | - | - | - |
| Projects | Full CRUD | View assigned | View assigned | View all |
| Leads | Full CRUD | - | Full CRUD | View all |
| Daily Reports | View all | Create/Edit own | Create/Edit own | - |
| Expenses | Approve/Reject | Submit own | Submit own | - |
| Payments | Full CRUD | - | - | Full CRUD |

## Navigation Features
- Role-based menu visibility
- User role display in navigation
- Quick action buttons based on role
- Dashboard content customized per role

## Security Features
- CSRF protection through session-based auth
- Password hashing with PHP's password_hash()
- Role-based access control middleware
- Input validation and sanitization
- SQL injection prevention with prepared statements

## Usage Examples

### Creating a New User (Admin only)
1. Login as Admin
2. Navigate to Users → Create User
3. Fill in user details and assign role
4. User can login with provided credentials

### Submitting an Expense (Non-investors)
1. Navigate to Expenses → Create Expense
2. Fill in expense details
3. Admin will see it in pending expenses for approval

### Managing Leads (BD role)
1. Navigate to Leads → Create Lead
2. Add company and contact information
3. Track lead status through the pipeline

### Processing Payments (Investor role)
1. Navigate to Payments → Create Payment
2. Specify project, amount, and purpose
3. Track payment status

## Tech Stack
- **Backend**: Core PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: Vanilla CSS (Black & White theme)
- **Architecture**: MVC Pattern
- **Session**: PHP Native Sessions
- **Security**: Password hashing, prepared statements

## Development Notes
- All controllers use the Auth middleware for role-based access
- Views consistently use admin_layout.php for styling
- Database operations use prepared statements
- Session flash messages for user feedback
- Responsive design for mobile compatibility

The application is now complete with full role-based access control, a professional black & white theme, and all requested functionality implemented.

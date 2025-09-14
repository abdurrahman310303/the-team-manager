# Team Manager - Core PHP Application

A complete team management system built with pure PHP featuring role-based access control and a clean black & white theme.

## 🚀 Features

- **Role-Based Access Control**: Admin, Developer, Investor, BD roles with specific permissions
- **Project Management**: Create, track, and manage development projects  
- **Lead Management**: Business development and customer relationship management
- **Daily Reports**: Work tracking and progress reporting
- **Expense Management**: Project expense tracking with approval workflow
- **Payment System**: Investment and financial transaction management
- **User Management**: Complete user and role administration
- **Clean Black & White Theme**: Consistent, professional interface for all users

## 📁 Project Structure

```
core-php-app/
├── config/
│   └── database.php          # Database configuration
├── core/
│   ├── Auth.php              # Authentication middleware
│   ├── Database.php          # Database connection
│   ├── Router.php            # URL routing with parameter support
│   └── Session.php           # Session management
├── models/
│   ├── User.php              # User and role models
│   ├── Project.php           # Project management
│   ├── Lead.php              # Lead tracking
│   ├── DailyReport.php       # Daily reporting
│   ├── Expense.php           # Expense management
│   └── Payment.php           # Payment processing
├── controllers/
│   ├── AuthController.php    # Login/logout/register
│   ├── DashboardController.php # Role-based dashboards
│   ├── ProjectController.php # Project CRUD
│   ├── LeadController.php    # Lead management
│   ├── DailyReportController.php # Report management
│   ├── ExpenseController.php # Expense workflow
│   ├── PaymentController.php # Payment processing
│   ├── UserController.php    # User management
│   └── RoleController.php    # Role administration
├── views/
│   ├── admin_layout.php      # Main layout (black & white theme)
│   ├── auth/                 # Authentication views
│   ├── dashboard/            # Role-specific dashboards
│   ├── users/                # User management views
│   ├── projects/             # Project views
│   ├── leads/                # Lead management views
│   ├── daily-reports/        # Reporting views
│   ├── expenses/             # Expense views
│   └── payments/             # Payment views
├── uploads/                  # File uploads directory
├── database.sql              # Complete database schema
├── SETUP_GUIDE.md           # Detailed setup instructions
├── index.php                # Application entry point
└── router.php               # Router for built-in server
```

## 🛠 Quick Installation

1. **Database Setup**:
   ```bash
   mysql -u root -p < database.sql
   ```

2. **Configure Database**:
   Edit `config/database.php` with your credentials

3. **Start Application**:
   ```bash
   cd core-php-app
   php -S localhost:8000 router.php
   ```

4. **Default Login**:
   - Email: `admin@teammanager.com`
   - Password: `password`

## 🎯 Role-Based Features

### Admin Role
- ✅ Full system access
- ✅ User and role management
- ✅ Project creation and management
- ✅ Lead oversight
- ✅ Expense approval/rejection
- ✅ Payment management
- ✅ All reporting features

### Developer Role
- ✅ View assigned projects
- ✅ Submit daily reports
- ✅ Track work hours
- ✅ Submit expenses
- ✅ Personal dashboard

### Business Development (BD) Role
- ✅ Create and manage leads
- ✅ Track lead pipeline
- ✅ Submit daily reports
- ✅ Submit expenses
- ✅ Lead performance metrics

### Investor Role
- ✅ View all projects (read-only)
- ✅ View leads (read-only)
- ✅ Manage payments
- ✅ Investment tracking
- ✅ Financial dashboards

## 🎨 Theme Features

- **Consistent Black & White Design**: Same professional interface for all roles
- **Role-Based Navigation**: Menu items show/hide based on user permissions
- **Responsive Layout**: Works on desktop and mobile devices
- **Clean Typography**: Easy to read and professional appearance
- **Intuitive Forms**: User-friendly input styles and validation

## 🔒 Security Features

- **Role-Based Access Control**: Centralized Auth middleware
- **Password Hashing**: PHP's password_hash() function
- **SQL Injection Protection**: Prepared statements
- **Session Security**: Secure session management
- **Input Validation**: Form validation and sanitization

## 📊 Database Schema

8 core tables with proper relationships:
- `users` & `roles` - User authentication and authorization
- `projects` - Project management
- `leads` - Business development tracking
- `daily_reports` - Work progress tracking
- `expenses` - Expense management with approval
- `payments` - Financial transactions
- Plus supporting tables for relationships

## 🚀 Deployment Ready

- **No Framework Dependencies**: Pure PHP for easy deployment
- **Simple File Structure**: Easy to understand and maintain
- **Database Included**: Complete schema with sample data
- **Production Ready**: Secure and optimized code

For detailed setup instructions, see `SETUP_GUIDE.md`.

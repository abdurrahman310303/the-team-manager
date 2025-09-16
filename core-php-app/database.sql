-- Team Manager Database Schema
-- Import this file into your existing database via phpMyAdmin

-- Roles table
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default roles
INSERT INTO roles (name, display_name, description) VALUES
('admin', 'Administrator', 'Full system access and management capabilities'),
('developer', 'Developer', 'Technical development and project implementation'),
('investor', 'Investor', 'Investment oversight and financial decision making'),
('bd', 'Business Development', 'Lead generation, client relations, and business growth');

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Projects table
CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('planning', 'in_progress', 'completed', 'on_hold', 'cancelled') DEFAULT 'planning',
    budget DECIMAL(15, 2) NULL,
    start_date DATE NULL,
    end_date DATE NULL,
    project_manager_id INT NOT NULL,
    client_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_manager_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Project Users table (many-to-many relationship)
CREATE TABLE project_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    role ENUM('developer', 'qa', 'designer', 'analyst', 'other') DEFAULT 'developer',
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_project_user (project_id, user_id)
);

-- Leads table
CREATE TABLE leads (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_name VARCHAR(255) NOT NULL,
    contact_person VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    description TEXT,
    status ENUM('new', 'contacted', 'qualified', 'proposal_sent', 'negotiating', 'closed_won', 'closed_lost') DEFAULT 'new',
    source ENUM('website', 'referral', 'cold_call', 'social_media', 'advertisement', 'other') DEFAULT 'website',
    estimated_value DECIMAL(15, 2) NULL,
    assigned_to INT NOT NULL,
    project_id INT NULL,
    last_contact_date DATE NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);

-- Daily Reports table
CREATE TABLE daily_reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    project_id INT NULL,
    report_type ENUM('developer', 'bd', 'general') NOT NULL,
    report_date DATE NOT NULL,
    work_completed TEXT NOT NULL,
    challenges_faced TEXT,
    next_plans TEXT,
    hours_worked INT DEFAULT 0,
    leads_generated INT DEFAULT 0,
    proposals_submitted INT DEFAULT 0,
    projects_locked INT DEFAULT 0,
    revenue_generated DECIMAL(15, 2) DEFAULT 0,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    UNIQUE KEY unique_daily_report (user_id, report_date, report_type)
);

-- Expenses table
CREATE TABLE expenses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    added_by INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    amount DECIMAL(15, 2) NOT NULL,
    category ENUM('development', 'marketing', 'infrastructure', 'tools', 'travel', 'other') NOT NULL,
    expense_date DATE NOT NULL,
    receipt_url VARCHAR(255) NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Payments table
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NULL,
    investor_id INT NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    exchange_rate DECIMAL(10, 4) NULL,
    amount_usd DECIMAL(15, 2) NULL,
    payment_type ENUM('investment', 'expense_reimbursement', 'profit_share', 'reimbursement', 'other') NOT NULL,
    fund_purpose ENUM('salaries', 'upwork_connects', 'project_expenses', 'office_rent', 'equipment', 'marketing', 'other') NOT NULL,
    is_project_related BOOLEAN DEFAULT FALSE,
    payment_method ENUM('bank_transfer', 'check', 'cash', 'other') NOT NULL,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    payment_date DATE NOT NULL,
    reference_number VARCHAR(255) NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (investor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Profit Shares table
CREATE TABLE profit_shares (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    percentage DECIMAL(5, 2) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    calculated_date DATE NOT NULL,
    paid_date DATE NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Proposals table
CREATE TABLE proposals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    proposed_budget DECIMAL(15, 2) NOT NULL,
    status ENUM('draft', 'submitted', 'under_review', 'accepted', 'rejected') DEFAULT 'draft',
    project_id INT NOT NULL,
    submitted_by INT NOT NULL,
    client_id INT NULL,
    submission_date DATE NULL,
    review_date DATE NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (submitted_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert a default admin user (password: 'password')
INSERT INTO users (name, email, password, role_id) VALUES
('Admin User', 'admin@teammanager.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- Insert sample projects
INSERT INTO projects (name, description, status, budget, project_manager_id) VALUES
('Website Redesign', 'Complete redesign of company website', 'in_progress', 5000.00, 1),
('Mobile App Development', 'iOS and Android app development', 'planning', 15000.00, 1),
('Database Migration', 'Migrate legacy database to new system', 'completed', 3000.00, 1);

# 🚀 Team Manager - Namecheap Shared Hosting Deployment Guide

## 📋 Pre-Deployment Checklist

### System Requirements ✅
- **PHP Version**: 7.4+ (Your app uses modern PHP features)
- **MySQL**: 5.7+ or MariaDB 10.2+
- **Extensions**: PDO, PDO_MySQL, mbstring, fileinfo
- **File Permissions**: Write access to uploads directory

### 🔧 Step 1: Prepare Your Local Files

1. **Run the deployment preparation script:**
   ```bash
   ./prepare-deployment.sh
   ```
   This creates a `deployment/team-manager-deploy.zip` file ready for upload.

---

## 🌐 Step 2: Namecheap cPanel Setup

### A. Access Your cPanel
1. Go to: `https://cpanel.test.gojins.com` or through Namecheap dashboard
2. Login with your hosting credentials

### B. Create MySQL Database
1. **Find "MySQL Databases" in cPanel**
2. **Create Database:**
   - Database Name: `team_manager` (will become `yourusername_team_manager`)
   - Click "Create Database"
3. **Create Database User:**
   - Username: `tm_admin` (will become `yourusername_tm_admin`)
   - Password: Use a strong password (save this!)
   - Click "Create User"
4. **Assign User to Database:**
   - Select your database and user
   - Grant "ALL PRIVILEGES"
   - Click "Make Changes"

### C. Note Your Database Credentials
```
Host: localhost
Database: yourusername_team_manager
Username: yourusername_tm_admin
Password: [your chosen password]
```

---

## 📁 Step 3: File Upload & Configuration

### A. Upload Files via File Manager
1. **Open "File Manager" in cPanel**
2. **Navigate to `public_html/`**
3. **Create subdirectory** (optional): `team-manager/` for organized structure
4. **Upload `team-manager-deploy.zip`**
5. **Extract the archive**
6. **Delete the zip file after extraction**

### B. Configure Database Connection
1. **Edit `config/database.php`:**
   ```php
   return [
       'host' => 'localhost',
       'dbname' => 'yourusername_team_manager', // Replace with actual
       'username' => 'yourusername_tm_admin',   // Replace with actual
       'password' => 'your_strong_password',     // Replace with actual
       'charset' => 'utf8mb4',
       'options' => [
           PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
           PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
           PDO::ATTR_EMULATE_PREPARES => false,
       ]
   ];
   ```

### C. Set File Permissions
1. **In File Manager, select all files/folders**
2. **Click "Permissions"**
3. **Set permissions:**
   - Folders: `755`
   - Files: `644`
   - `uploads/` folder: `755` (with write access)

---

## 🗄️ Step 4: Database Setup

### A. Import Database Structure
1. **Open "phpMyAdmin" in cPanel**
2. **Select your database** (`yourusername_team_manager`)
3. **Click "Import" tab**
4. **Choose file**: Upload your `database.sql`
5. **Click "Go"**

### B. Verify Database Import
Check that these tables were created:
- `roles`
- `users` 
- `projects`
- `leads`
- `payments`
- `expenses`
- `daily_reports`
- `project_assignments`
- `lead_assignments`

---

## 🔗 Step 5: Domain & URL Configuration

### A. If using subdirectory (`test.gojins.com/team-manager/`):
1. **Access your app at**: `https://test.gojins.com/team-manager/`
2. **Update any hardcoded URLs** in your code if needed

### B. If using root domain (`test.gojins.com`):
1. **Move all files from subdirectory to `public_html/`**
2. **Access your app at**: `https://test.gojins.com/`

### C. SSL Certificate
1. **In cPanel, find "SSL/TLS"**
2. **Enable "Let's Encrypt" for test.gojins.com**
3. **Force HTTPS redirects**

---

## 👤 Step 6: Create Admin User

### A. Access Your Application
1. Navigate to: `https://test.gojins.com/team-manager/` (or root)
2. You should see the login page

### B. Create Admin User via Database
**Option 1: Via phpMyAdmin**
```sql
INSERT INTO users (name, email, password, role_id, created_at, updated_at) 
VALUES (
    'Administrator', 
    'admin@gojins.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    1, 
    NOW(), 
    NOW()
);
```

**Option 2: Create registration script (temporary)**
Create `setup_admin.php` in your web directory:
```php
<?php
require_once 'core/Database.php';
require_once 'models/User.php';

$user = new User();
$adminData = [
    'name' => 'Administrator',
    'email' => 'admin@gojins.com',
    'password' => password_hash('your_secure_password', PASSWORD_DEFAULT),
    'role_id' => 1 // Admin role
];

if ($user->create($adminData)) {
    echo "Admin user created successfully!";
    echo "<br>Email: admin@gojins.com";
    echo "<br>Password: your_secure_password";
    echo "<br><strong>Delete this file immediately!</strong>";
} else {
    echo "Error creating admin user.";
}
?>
```

**⚠️ IMPORTANT: Delete `setup_admin.php` after use!**

---

## ✅ Step 7: Testing & Verification

### A. Test Core Functionality
1. **Login**: Use admin credentials
2. **Dashboard**: Check if stats load correctly
3. **Users**: Try creating a new user
4. **Projects**: Create a test project
5. **Leads**: Create a test lead
6. **File Upload**: Test receipt/report uploads

### B. Test Role-Based Access
1. **Create test users** for each role:
   - Developer
   - Investor  
   - Business Development
2. **Login as each role** and verify:
   - Correct navigation items
   - Appropriate data visibility
   - Proper permissions

### C. Check File Uploads
1. **Test upload functionality** in:
   - Daily reports
   - Payment receipts
2. **Verify files are saved** in `uploads/` directory
3. **Check file permissions** (should be readable)

---

## 🛠️ Troubleshooting Common Issues

### Database Connection Errors
```
1. Verify database credentials in config/database.php
2. Check if database user has proper privileges
3. Ensure database exists and is accessible
```

### File Permission Issues
```
1. Set uploads/ directory to 755 or 775
2. Ensure web server can write to uploads/
3. Check .htaccess file is present and readable
```

### 404 Errors / Routing Issues
```
1. Verify .htaccess file is in root directory
2. Check if mod_rewrite is enabled (usually is on Namecheap)
3. Test with index.php in URL: /index.php/dashboard
```

### PHP Errors
```
1. Enable error reporting temporarily:
   Add to index.php: ini_set('display_errors', 1);
2. Check cPanel Error Logs
3. Verify PHP version compatibility
```

---

## 🔒 Security Checklist

### A. Post-Deployment Security
- [ ] Change default admin password
- [ ] Remove any setup/debug files
- [ ] Set strong database passwords  
- [ ] Enable HTTPS/SSL
- [ ] Regular backups via cPanel

### B. File Protection
- [ ] Ensure uploads/ directory has proper permissions
- [ ] Verify .htaccess is protecting sensitive files
- [ ] Check that config files aren't directly accessible

---

## 📞 Support Resources

### Namecheap Resources
- **Knowledge Base**: https://www.namecheap.com/support/knowledgebase/
- **Live Chat**: Available 24/7
- **Shared Hosting Guide**: Specific documentation for shared hosting

### Application Support
- Check application logs in uploads/logs/ (if logging is enabled)
- Monitor cPanel Error Logs
- Test in development environment first

---

## 🎉 Deployment Complete!

Your Team Manager application should now be live at:
**https://test.gojins.com/team-manager/**

### Default Login (change immediately):
- **Email**: admin@gojins.com  
- **Password**: [your chosen password]

### Next Steps:
1. Change admin password
2. Create user accounts for your team
3. Set up regular database backups
4. Monitor application performance
5. Keep the system updated

---

*📝 Save this guide for future reference and updates!*

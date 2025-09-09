# Team Manager - Business Management System

A comprehensive Laravel-based business management system designed for a team of 6 people with role-based access control.

## Features

### User Roles
- **Admin**: Full system access and management capabilities
- **Developer**: Technical development and project implementation
- **Investor**: Investment oversight and financial decision making
- **Business Development (BD)**: Lead generation, client relations, and business growth

### Core Modules

#### 1. Projects Management
- Create and manage projects
- Track project status (planning, in_progress, completed, on_hold, cancelled)
- Assign project managers and clients
- Budget tracking
- Timeline management

#### 2. Proposals Tracking
- Submit business proposals
- Track proposal status (draft, submitted, under_review, accepted, rejected)
- Budget proposals
- Client assignment
- Review and approval workflow

#### 3. Leads Generation
- Lead management system
- Track lead status (new, contacted, qualified, proposal_sent, negotiating, closed_won, closed_lost)
- Lead source tracking (website, referral, cold_call, social_media, advertisement, other)
- Value estimation
- Assignment to team members

#### 4. Role-Based Dashboards
- **Admin Dashboard**: Overview of all projects, proposals, leads, and team members
- **Developer Dashboard**: Personal projects and proposals
- **Investor Dashboard**: Investment overview and ROI tracking
- **BD Dashboard**: Lead management and conversion tracking

## Installation

1. **Prerequisites**
   - PHP 8.1 or higher
   - Composer
   - MySQL 5.7 or higher
   - Node.js and NPM

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Update your `.env` file with MySQL credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=team_manager
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Database Setup**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

## Default Users

The system comes with pre-seeded users for each role:

- **Admin**: admin@teammanager.com
- **Developer**: developer@teammanager.com
- **Investor**: investor@teammanager.com
- **BD**: bd@teammanager.com

All users have the password: `password`

## Database Structure

### Tables
- `users` - User accounts with role assignments
- `roles` - User roles (admin, developer, investor, bd)
- `projects` - Project management
- `proposals` - Business proposals
- `leads` - Lead generation and tracking

### Relationships
- Users belong to Roles
- Projects have Project Managers and Clients (both Users)
- Proposals belong to Projects and are submitted by Users
- Leads are assigned to Users and can be linked to Projects

## Usage

1. **Login**: Use any of the default user accounts to log in
2. **Dashboard**: Each role sees a customized dashboard with relevant information
3. **Navigation**: Use the navigation menu to access different modules
4. **Role-Based Access**: Features are restricted based on user roles

## Development

### Adding New Features
1. Create migrations for database changes
2. Update models with relationships
3. Create controllers for business logic
4. Add views for user interface
5. Update routes for navigation

### Customization
- Modify dashboard views in `resources/views/dashboard/`
- Add new roles in the `RoleSeeder`
- Customize user permissions in controllers
- Update styling in `resources/css/`

## Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **Styling**: Tailwind CSS
- **Icons**: Heroicons

## License

This project is proprietary software for internal team use.
<?php
/**
 * Authentication and Authorization Helper
 */

class Auth {
    
    /**
     * Check if user is authenticated
     */
    public static function check() {
        return Session::isLoggedIn();
    }
    
    /**
     * Require authentication or redirect to login
     */
    public static function requireAuth() {
        if (!self::check()) {
            Session::flash('error', 'Please log in to access this page');
            header('Location: /login');
            exit;
        }
    }
    
    /**
     * Get current user
     */
    public static function user() {
        return Session::getUser();
    }
    
    /**
     * Check if user has specific role
     */
    public static function hasRole($role) {
        $user = self::user();
        return $user && $user['role_name'] === $role;
    }
    
    /**
     * Check if user has any of the specified roles
     */
    public static function hasAnyRole($roles) {
        $user = self::user();
        if (!$user) return false;
        
        if (is_string($roles)) {
            $roles = [$roles];
        }
        
        return in_array($user['role_name'], $roles);
    }
    
    /**
     * Require specific role or show access denied
     */
    public static function requireRole($role, $message = 'Access denied. Insufficient permissions.') {
        self::requireAuth();
        
        if (!self::hasRole($role)) {
            self::accessDenied($message);
        }
    }
    
    /**
     * Require any of the specified roles
     */
    public static function requireAnyRole($roles, $message = 'Access denied. Insufficient permissions.') {
        self::requireAuth();
        
        if (!self::hasAnyRole($roles)) {
            self::accessDenied($message);
        }
    }
    
    /**
     * Admin access required
     */
    public static function requireAdmin() {
        self::requireRole('admin', 'Administrator access required.');
    }
    
    /**
     * BD or Admin access required
     */
    public static function requireBDOrAdmin() {
        self::requireAnyRole(['bd', 'admin'], 'Business Development or Administrator access required.');
    }
    
    /**
     * Investor or Admin access required
     */
    public static function requireInvestorOrAdmin() {
        self::requireAnyRole(['investor', 'admin'], 'Investor or Administrator access required.');
    }
    
    /**
     * Admin or Investor access required
     */
    public static function requireAdminOrInvestor() {
        self::requireAnyRole(['admin', 'investor'], 'Administrator or Investor access required.');
    }
    
    /**
     * Non-investor access (everyone except investor)
     */
    public static function requireNonInvestor() {
        self::requireAuth();
        
        if (self::hasRole('investor')) {
            self::accessDenied('This feature is not available for investors.');
        }
    }
    
    /**
     * Show access denied message and redirect
     */
    private static function accessDenied($message) {
        Session::flash('error', $message);
        header('Location: /dashboard');
        exit;
    }
    
    /**
     * Check if current user can manage users
     */
    public static function canManageUsers() {
        return self::hasRole('admin');
    }
    
    /**
     * Check if current user can manage projects
     */
    public static function canManageProjects() {
        return self::hasRole('admin');
    }
    
    /**
     * Check if current user can manage leads
     */
    public static function canManageLeads() {
        return self::hasAnyRole(['bd', 'admin']);
    }
    
    /**
     * Check if current user can create leads
     */
    public static function canCreateLeads() {
        return self::hasAnyRole(['bd', 'admin']);
    }
    
    /**
     * Check if current user can manage payments
     */
    public static function canManagePayments() {
        return self::hasAnyRole(['investor', 'admin']);
    }
    
    /**
     * Check if current user can view payments
     */
    public static function canViewPayments() {
        return self::hasAnyRole(['investor', 'admin']);
    }
    
    /**
     * Check if current user can submit daily reports
     */
    public static function canSubmitReports() {
        return !self::hasRole('investor'); // Everyone except investors
    }
    
    /**
     * Check if current user can submit expenses
     */
    public static function canSubmitExpenses() {
        return !self::hasRole('investor'); // Everyone except investors
    }
    
    /**
     * Check if current user can approve expenses
     */
    public static function canApproveExpenses() {
        return self::hasRole('admin');
    }
    
    /**
     * Check if user can view specific lead
     */
    public static function canViewLead($lead) {
        $user = self::user();
        
        // Admin can view all
        if (self::hasRole('admin')) return true;
        
        // BD can view leads they created or are assigned to
        if (self::hasRole('bd')) {
            if (isset($lead['created_by']) && $lead['created_by'] == $user['id']) return true;
            if ($lead['assigned_to'] == $user['id']) return true;
        }
        
        // Investors can view all leads
        if (self::hasRole('investor')) return true;
        
        return false;
    }
    
    /**
     * Check if user can edit specific lead
     */
    public static function canEditLead($lead) {
        $user = self::user();
        
        // Admin can edit all
        if (self::hasRole('admin')) return true;
        
        // BD can edit leads they created
        if (self::hasRole('bd') && isset($lead['created_by']) && $lead['created_by'] == $user['id']) return true;
        
        return false;
    }
    
    /**
     * Check if user can view specific daily report
     */
    public static function canViewReport($report) {
        $user = self::user();
        
        // Admin can view all
        if (self::hasRole('admin')) return true;
        
        // Users can view their own reports
        if ($report['user_id'] == $user['id']) return true;
        
        return false;
    }
    
    /**
     * Check if user can edit specific daily report
     */
    public static function canEditReport($report) {
        $user = self::user();
        
        // Admin can edit all
        if (self::hasRole('admin')) return true;
        
        // Users can edit their own reports (within 24 hours)
        if ($report['user_id'] == $user['id']) {
            $reportDate = strtotime($report['created_at']);
            $now = time();
            $hoursSinceCreation = ($now - $reportDate) / 3600;
            
            return $hoursSinceCreation <= 24; // Allow editing within 24 hours
        }
        
        return false;
    }
}

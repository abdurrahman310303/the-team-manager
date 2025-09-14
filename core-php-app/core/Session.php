<?php
/**
 * Session Management Class
 */

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        self::start();
        unset($_SESSION[$key]);
    }
    
    public static function destroy() {
        self::start();
        session_destroy();
    }
    
    public static function flash($key, $value) {
        self::set('flash_' . $key, $value);
    }
    
    public static function getFlash($key) {
        $value = self::get('flash_' . $key);
        self::remove('flash_' . $key);
        return $value ?: '';
    }
    
    public static function isLoggedIn() {
        return self::has('user_id');
    }
    
    public static function getUser() {
        return self::get('user');
    }
    
    public static function setUser($user) {
        self::set('user_id', $user['id']);
        self::set('user', $user);
    }
    
    public static function logout() {
        self::remove('user_id');
        self::remove('user');
    }
}

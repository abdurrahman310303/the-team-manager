<?php
/**
 * Core Database Connection Class
 */

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $configPath = __DIR__ . '/../config/database.php';
        
        // Always include instead of require_once to avoid the "true" return issue
        $config = include $configPath;
        
        // If config is not an array, something went wrong
        if (!is_array($config)) {
            throw new Exception("Database configuration is invalid or missing");
        }
        
        try {
            $this->connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->execute($params);
        if (!$result) {
            throw new Exception('Query execution failed: ' . implode(', ', $stmt->errorInfo()));
        }
        return $stmt;
    }
    
    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}

<?php
/**
 * Router Class for handling URL routing
 */

class Router {
    private $routes = [];
    
    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }
    
    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }
    
    public function put($path, $callback) {
        $this->routes['PUT'][$path] = $callback;
    }
    
    public function delete($path, $callback) {
        $this->routes['DELETE'][$path] = $callback;
    }
    
    public function resolve() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Handle PUT and DELETE methods via _method parameter
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        
        // First try exact match
        $callback = $this->routes[$method][$path] ?? null;
        
        if ($callback) {
            return $this->executeCallback($callback);
        }
        
        // Try pattern matching for dynamic routes
        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            if ($this->matchRoute($route, $path)) {
                return $this->executeCallback($callback);
            }
        }
        
        // Route not found
        http_response_code(404);
        echo "404 - Page Not Found - Path: $path";
        exit;
    }
    
    private function matchRoute($route, $path) {
        // Convert route pattern to regex
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';
        
        return preg_match($pattern, $path);
    }
    
    private function executeCallback($callback) {
        if (is_callable($callback)) {
            return call_user_func($callback);
        }
        
        if (is_array($callback)) {
            $controller = new $callback[0]();
            return call_user_func([$controller, $callback[1]]);
        }
        
        return $callback;
    }
}

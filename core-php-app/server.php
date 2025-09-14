<?php
/**
 * Router for PHP built-in development server
 * This file handles URL rewriting for the built-in server
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Remove query parameters for routing
$uri = strtok($uri, '?');

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Serve the requested resource as-is
}

// Route all other requests through index.php
require_once __DIR__ . '/index.php';

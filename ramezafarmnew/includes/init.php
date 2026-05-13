<?php
/**
 * Master Initialization File
 * includes/init.php
 * 
 * This file centralizes all initializations and configurations
 * Include this file at the beginning of every PHP page
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable in production
ini_set('log_errors', 1);

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Include all configuration and utility files
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/components.php';
require_once __DIR__ . '/db-helper.php';

// Initialize database connection
$db = get_db();

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');

// Log page access
if (isset($_GET) || isset($_POST)) {
    $route = $_SERVER['REQUEST_URI'];
    log_message("Page accessed: $route", 'info');
}

?>

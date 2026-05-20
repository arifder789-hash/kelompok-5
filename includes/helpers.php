<?php
/**
 * Helper Functions
 * includes/helpers.php
 */

/**
 * Safely output text to prevent XSS
 */
function e($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Format currency (Indonesian Rupiah)
 */
function format_currency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Format date (Indonesian format)
 */
function format_date($date, $format = 'd M Y') {
    $months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $timestamp = strtotime($date);
    $day = date('d', $timestamp);
    $month = $months[(int)date('m', $timestamp) - 1];
    $year = date('Y', $timestamp);
    
    return "$day $month $year";
}

/**
 * Redirect to URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Get base URL
 */
function base_url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

/**
 * Get asset URL
 */
function asset_url($path = '') {
    return ASSETS_PATH . '/' . ltrim($path, '/');
}

/**
 * Set flash message (session-based)
 */
function set_flash($message, $type = 'info') {
    if (!isset($_SESSION)) {
        session_start();
    }
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

/**
 * Get and clear flash message
 */
function get_flash() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        
        return ['message' => $message, 'type' => $type];
    }
    
    return null;
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    if (!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['user_id']);
}

/**
 * Get current user data
 */
function get_user() {
    if (!isset($_SESSION)) {
        session_start();
    }
    return $_SESSION['user'] ?? null;
}

/**
 * Validate email
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Indonesian format)
 */
function is_valid_phone($phone) {
    $phone = preg_replace('/\D/', '', $phone);
    return preg_match('/^(\+62|62|0)[0-9]{9,12}$/', $phone) === 1;
}

/**
 * Sanitize input
 */
function sanitize($input) {
    return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
}

/**
 * Generate CSRF token
 */
function generate_csrf_token() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verify_csrf_token($token) {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

/**
 * Get image URL with fallback
 */
function get_image_url($image_path, $fallback = null) {
    if (file_exists($image_path) && $image_path) {
        return $image_path;
    }
    return $fallback ?? 'https://via.placeholder.com/400x300?text=No+Image';
}

/**
 * Truncate text
 */
function truncate_text($text, $limit = 100, $suffix = '...') {
    if (strlen($text) <= $limit) {
        return $text;
    }
    
    return substr($text, 0, $limit) . $suffix;
}

/**
 * Convert slug to title
 */
function slug_to_title($slug) {
    return ucwords(str_replace(['-', '_'], ' ', $slug));
}

/**
 * Convert title to slug
 */
function title_to_slug($title) {
    return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
}

/**
 * Get file extension
 */
function get_file_extension($filename) {
    return pathinfo($filename, PATHINFO_EXTENSION);
}

/**
 * Check if file is image
 */
function is_image($filename) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(get_file_extension($filename));
    return in_array($ext, $allowed);
}

/**
 * Generate unique filename
 */
function generate_unique_filename($original_filename) {
    $ext = get_file_extension($original_filename);
    return time() . '_' . uniqid() . '.' . $ext;
}

/**
 * Log message
 */
function log_message($message, $type = 'info') {
    $log_file = __DIR__ . '/../logs/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$type] $message\n";
    
    if (!is_dir(dirname($log_file))) {
        mkdir(dirname($log_file), 0755, true);
    }
    
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

/**
 * API Response
 */
function api_response($success = true, $message = '', $data = null, $status_code = 200) {
    header('Content-Type: application/json');
    http_response_code($status_code);
    
    return json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
}

?>

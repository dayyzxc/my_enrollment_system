<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'my_enrollment_system');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application configuration
define('APP_NAME', 'NCST Enrollment System');
define('BASE_URL', 'http://localhost/my_enrollment_system/');
define('UPLOAD_PATH', 'uploads/');

// Session configuration (must be set before session_start)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
    session_start();
}

// Timezone
date_default_timezone_set('Asia/Manila');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
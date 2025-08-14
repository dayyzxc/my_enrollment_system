logout
<?php
require_once 'config.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    $user = getCurrentUser();
    logActivity("User logout: " . $user['student_id'], $user['id'], 'logout');
} elseif (isAdmin()) {
    $admin = getCurrentAdmin();
    logActivity("Admin logout: " . $admin['username'], null, 'logout');
}

// Clear all session data
$_SESSION = array();

// Delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to home page
header('Location: index.php');
exit();
?>
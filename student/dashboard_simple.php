<?php
// ========================= student/dashboard_simple.php =========================
// Simple version to test if the dashboard works without errors

// Enable all error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Testing Dashboard Load...</h1>";

// Test 1: Session start
try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    echo "<p style='color: green;'>✓ Session started successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Session error: " . $e->getMessage() . "</p>";
}

// Test 2: Check for student session
echo "<p>Current session data:</p><ul>";
foreach ($_SESSION as $key => $value) {
    echo "<li>$key: $value</li>";
}
echo "</ul>";

// Test 3: Test if user would be redirected (simulate no login)
if (!isset($_SESSION['student_id'])) {
    echo "<p style='color: orange;'>⚠ No student_id in session - would redirect to login</p>";
    echo "<p><strong>This is why your dashboard isn't loading!</strong></p>";
    
    // Set a test session for dashboard testing
    $_SESSION['student_id'] = 1;
    $_SESSION['student_name'] = 'Test Student';
    $_SESSION['student_email'] = 'test@example.com';
    $_SESSION['student_course'] = 'Test Course';
    echo "<p style='color: blue;'>ℹ Set test session data for dashboard testing</p>";
}

// Test 4: Try loading required files
echo "<h3>Testing Required Files:</h3>";

try {
    require_once __DIR__ . '/../config.php';
    echo "<p style='color: green;'>✓ config.php loaded</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ config.php error: " . $e->getMessage() . "</p>";
}

try {
    require_once __DIR__ . '/../includes/database.php';
    echo "<p style='color: green;'>✓ database.php loaded</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ database.php error: " . $e->getMessage() . "</p>";
}

try {
    require_once __DIR__ . '/../includes/functions.php';
    echo "<p style='color: green;'>✓ functions.php loaded</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ functions.php error: " . $e->getMessage() . "</p>";
}

// Test 5: Database query
try {
    $db = Database::getInstance();
    $userId = $_SESSION['student_id'];
    $user = $db->fetch("SELECT * FROM students WHERE id = ?", [$userId]);
    
    if ($user) {
        echo "<p style='color: green;'>✓ Student found: " . $user['name'] . "</p>";
    } else {
        echo "<p style='color: red;'>✗ No student found with ID: $userId</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database query error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<p>1. <a href='dashboard.php'>Try loading the actual dashboard</a></p>";
echo "<p>2. <a href='../pages/login.php'>Go back to login</a></p>";
echo "<p>3. If dashboard loads now, the issue was the missing session data</p>";
?>
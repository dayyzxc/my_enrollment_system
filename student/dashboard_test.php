<?php
// ========================= student/dashboard_test.php =========================
// Simple test to see if we can access files in the student directory

echo "<h1>Dashboard Test</h1>";
echo "<p>If you can see this, the file path works!</p>";
echo "<p>Current file location: " . __FILE__ . "</p>";
echo "<p>Directory contents:</p><ul>";

// List files in current directory
$files = scandir(__DIR__);
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "<li>$file</li>";
    }
}

echo "</ul>";

// Test if dashboard.php exists
if (file_exists(__DIR__ . '/dashboard.php')) {
    echo "<p style='color: green;'>✓ dashboard.php EXISTS in this directory</p>";
} else {
    echo "<p style='color: red;'>✗ dashboard.php NOT FOUND in this directory</p>";
}

// Test session functionality
session_start();
$_SESSION['test'] = 'Session works!';
echo "<p>Session test: " . $_SESSION['test'] . "</p>";

echo "<h3>Debugging Info:</h3>";
echo "<p>Server Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Current Directory: " . __DIR__ . "</p>";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

// Test database connection
try {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../includes/database.php';
    $db = Database::getInstance();
    echo "<p style='color: green;'>✓ Database connection works!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}
?>
<?php
// ========================= pages/register.php =========================

// (optional) show PHP errors while debugging – remove in production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- DB connection ---
$host = "localhost";
$dbname = "my_enrollment_system";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// --- Handle form submission ---
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name          = trim($_POST['name'] ?? '');
    $student_id    = trim($_POST['student_id'] ?? '');
    $course        = trim($_POST['course'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $password      = $_POST['password'] ?? '';
    $confirm_pass  = $_POST['confirm_password'] ?? '';

    // Basic validation
    if ($name === '' || $student_id === '' || $course === '' || $email === '' || $password === '' || $confirm_pass === '') {
        $message = '<div class="alert alert-danger">Please fill in all required fields.</div>';
    } elseif ($password !== $confirm_pass) {
        $message = '<div class="alert alert-danger">Passwords do not match.</div>';
    } else {
        try {
            // Duplicate check
            $check = $pdo->prepare("SELECT id FROM students WHERE email = ? OR student_id = ? LIMIT 1");
            $check->execute([$email, $student_id]);

            if ($check->fetch()) {
                $message = '<div class="alert alert-warning">Email or Student ID already exists.</div>';
            } else {
                // Insert
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO students (student_id, name, email, course, password, status)
                    VALUES (?, ?, ?, ?, ?, 'pending')
                ");
                $stmt->execute([$student_id, $name, $email, $course, $hashed]);

                // Success
                $message = '<div class="alert alert-success">Registration successful! Redirecting to login…</div>';
                // echo '<meta http-equiv="refresh" content="1.5;url=student/login.php">';
            //     header('Location: student/login.php');
            //     exit;
            //     function redirect($url, $statusCode = 303) {
            //         header('Location: ' . $url, true, $statusCode);
            //         die();
            //     }
            // }
                redirect('student/login.php');
        }
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                $message = '<div class="alert alert-warning">Email or Student ID already exists.</div>';
            } else {
                $message = '<div class="alert alert-danger">Database Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Registration</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-header" style="background: linear-gradient(90deg, #3b2066 0%, #a78bfa 100%); color: #ffffff;">
                    <h4 class="mb-0">Student Registration</h4>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>

                    <!-- Fixed form action: post back to itself -->
                    <form method="POST" action="" id="registration-form">
                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Student ID *</label>
                            <input type="text" name="student_id" class="form-control" placeholder="e.g., 2024-0001" required>
                            <small class="text-muted">Format: Letters, numbers, and hyphens only (5-20 characters)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Course *</label>
                            <select name="course" class="form-select" required>
                                <option value="">Select your course</option>
                                <option value="BSIT">BS Information Technology</option>
                                <option value="BSCS">BS Computer Science</option>
                                <option value="BSBA">BS Business Administration</option>
                                <option value="BSED">BS Education</option>
                                <option value="BSN">BS Nursing</option>
                                <option value="BSEE">BS Electrical Engineering</option>
                                <option value="BSCE">BS Civil Engineering</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" placeholder="your.email@example.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
                        </div>

                        <button type="submit" name="register" class="btn btn-primary w-100">
                            Register Account
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <p>Already have an account?
                            <a href="login.php">Login here</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="../index.php" class="text-decoration-none">Back to Home</a>
            </div>

        </div>
    </div>
</div>

</body>
</html>

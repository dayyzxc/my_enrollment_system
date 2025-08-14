<?php
// ========================= pages/login.php =========================
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$error_message = '';
$success_message = '';
$debug_info = [];

// Check if user is already logged in as student
if (isset($_SESSION['student_id'])) {
    header('Location: ../student/dashboard.php');
    exit;
}

// Check if user is logged in as admin and redirect appropriately
if (isset($_SESSION['admin_id'])) {
    if (isset($_POST['clear_admin'])) {
        session_destroy();
        session_start();
        $success_message = 'Admin session cleared. You can now login as a student.';
    }
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['login']) || !empty($_POST['login_id']))) {
    $login_id = trim($_POST['login_id'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($login_id) || empty($password)) {
        $error_message = 'Please fill in all required fields.';
    } else {
        try {
            $db = Database::getInstance();

            // Check if login_id is email or student_id
            $query = "SELECT * FROM students WHERE email = ? OR student_id = ? LIMIT 1";
            $student = $db->fetch($query, [$login_id, $login_id]);

            if ($student) {
                // Try password_verify (bcrypt/argon2)
                if (password_verify($password, $student['password'])) {
                    session_destroy();
                    session_start();
                    $_SESSION['student_id'] = $student['id'];
                    $_SESSION['student_name'] = $student['name'];
                    $_SESSION['student_email'] = $student['email'];
                    $_SESSION['student_course'] = $student['course'];
                    header('Location: ../student/dashboard.php');
                    exit;

                // Try MD5
                } elseif (md5($password) === $student['password']) {
                    session_destroy();
                    session_start();
                    $_SESSION['student_id'] = $student['id'];
                    $_SESSION['student_name'] = $student['name'];
                    $_SESSION['student_email'] = $student['email'];
                    $_SESSION['student_course'] = $student['course'];
                    header('Location: ../student/dashboard.php');
                    exit;

                // Try plain text (legacy)
                } elseif ($password === $student['password']) {
                    session_destroy();
                    session_start();
                    $_SESSION['student_id'] = $student['id'];
                    $_SESSION['student_name'] = $student['name'];
                    $_SESSION['student_email'] = $student['email'];
                    $_SESSION['student_course'] = $student['course'];
                    header('Location: ../student/dashboard.php');
                    exit;

                } else {
                    $error_message = 'Invalid student ID/email or password.';
                }
            } else {
                $error_message = 'Invalid student ID/email or password.';
            }
        } catch (Exception $e) {
            $error_message = 'An error occurred during login. Please try again.';
            error_log('Login error: ' . $e->getMessage());
        }
    }
}
?>

<div class="container">
    <div class="form-container">
        <div class="form-header">
            <h2 class="form-title">Student Login</h2>
            <p class="form-subtitle">Access your enrollment dashboard</p>
        </div>

        <?php if (isset($_SESSION['admin_id'])): ?>
            <div class="alert alert-warning">
                <strong>Notice:</strong> You are currently logged in as Admin (<?php echo htmlspecialchars($_SESSION['admin_username']); ?>). 
                To login as a student, clear your admin session first.
            </div>
            <form method="POST" style="margin-bottom: 20px;">
                <button type="submit" name="clear_admin" class="btn btn-secondary">Clear Admin Session</button>
            </form>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="login-form">
            <div class="form-group">
                <label class="form-label">Student ID or Email *</label>
                <input type="text" name="login_id" class="form-input" 
                       placeholder="Enter your Student ID or Email" 
                       value="<?php echo isset($_POST['login_id']) ? htmlspecialchars($_POST['login_id']) : ''; ?>" 
                       required>
            </div>

            <div class="form-group">
                <label class="form-label">Password *</label>
                <div class="password-input-wrapper">
                    <input type="password" name="password" class="form-input" id="password" 
                           placeholder="Enter your password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="password-toggle-icon"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember_me" 
                           <?php echo isset($_POST['remember_me']) ? 'checked' : ''; ?>> 
                    Remember me
                </label>
            </div>

            <button type="submit" name="login" class="btn btn-primary btn-block" id="login-btn">
                Login
            </button>
            
            <div class="text-center mt-3">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
                <p><a href="#" onclick="alert('Please contact the registrar office for password reset.')">Forgot Password?</a></p>
            </div>
        </form>
    </div>
</div>

<style>
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-warning {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }
    .password-input-wrapper {
        position: relative;
    }
    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        padding: 0.25rem;
    }
    .password-toggle:hover {
        color: #333;
    }
</style>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('password-toggle-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.className = 'fas fa-eye-slash';
        } else {
            passwordInput.type = 'password';
            toggleIcon.className = 'fas fa-eye';
        }
    }
</script>

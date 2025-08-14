<?php
require_once 'config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';

$db = Database::getInstance();
$page = $_GET['page'] ?? 'home';
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['register'])) {
            $name = validateRequired($_POST['name'], 'Name');
            $student_id = validateRequired($_POST['student_id'], 'Student ID');
            $email = validateRequired($_POST['email'], 'Email');
            $course = validateRequired($_POST['course'], 'Course');
            $password = validateRequired($_POST['password'], 'Password');

            $name = validateLength($name, 2, 100, 'Name');
            $password = validateLength($password, 6, 255, 'Password');

            if (!validateEmail($email)) {
                throw new Exception('Invalid email format');
            }

            if (!validateStudentId($student_id)) {
                throw new Exception('Invalid student ID format');
            }

            // Check if student ID or email exists
            $existing = $db->fetch("SELECT id FROM students WHERE student_id = ? OR email = ?", [$student_id, $email]);
            if ($existing) {
                throw new Exception('Student ID or Email already exists');
            }

            // Insert new student
            $hashedPassword = hashPassword($password);
            $sql = "INSERT INTO students (name, student_id, email, course, password, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
            $db->execute($sql, [$name, $student_id, $email, $course, $hashedPassword]);

            logActivity("New student registration: $student_id", null, 'registration');
            echo '<script>alert("Registration successful! Please wait for admin approval."); window.location.href = "pages/login.php";</script>';
            exit;

        } elseif (isset($_POST['login'])) {
            $login_id = validateRequired($_POST['login_id'], 'Login ID');
            $password = validateRequired($_POST['password'], 'Password');

            $user = $db->fetch("SELECT * FROM students WHERE (student_id = ? OR email = ?) AND status = 'approved'", [$login_id, $login_id]);

            if (!$user || !verifyPassword($password, $user['password'])) {
                throw new Exception('Invalid credentials or account not approved');
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['course'] = $user['course'];

            logActivity("User login: " . $user['student_id'], $user['id'], 'login');
            redirect('student/dashboard.php');
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get flash messages
$success = getFlashMessage('success');
$error = getFlashMessage('error') ?: $error;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-image: url('backgroundphoto.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        .portal-hero {
            max-width: 900px;
            margin: 2.5rem auto 2rem auto;
            background: rgba(255,255,255,0.18);
            border-radius: 32px;
            box-shadow: 0 8px 32px rgba(109,40,217,0.12);
            padding: 2.5rem 2.5rem 2rem 2.5rem;
            text-align: center;
            backdrop-filter: blur(8px);
            border: 2px solid #ede9fe;
        }
        .portal-hero h1 {
            font-size: 2.8rem;
            font-weight: 900;
            color: #070111ff;
            margin-bottom: 0.5rem;
        }
        .portal-hero p {
            font-size: 1.25rem;
            color: #f9f9f9ff;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        .portal-nav {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .portal-nav a {
            background: #ede9fe;
            color: #3b2066;
            font-weight: 600;
            padding: 0.7rem 2.2rem;
            border-radius: 18px;
            text-decoration: none;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(109,40,217,0.08);
            transition: background 0.2s, box-shadow 0.2s, color 0.2s;
        }
        .portal-nav a.active, .portal-nav a:hover {
            background: #6d28d9;
            color: #fff;
            box-shadow: 0 4px 16px rgba(109,40,217,0.13);
        }
        .main-content {
            max-width: 900px;
            margin: 0 auto 2.5rem auto;
            padding: 2rem 1.5rem 2.5rem 1.5rem;
            background: rgba(255,255,255,0.85);
            border-radius: 24px;
            box-shadow: 0 4px 24px rgba(109,40,217,0.10);
        }
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .alert-success {
            background: #bbf7d0;
            color: #166534;
            border: 1px solid #4ade80;
        }
        @media (max-width: 700px) {
            .portal-hero, .main-content { padding: 1.2rem 0.5rem; }
            .portal-hero h1 { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="portal-hero">
    <h1><i class="fas fa-school"></i> NCST ENROLLMENT</h1>
        <p>The NCST Educational System refers to the structured framework through which education is delivered to individuals within a society or country. It consists of various institutions, policies, curricula, and methods used to impart knowledge, skills, and values to students at different levels of their education.</p>
        <nav class="portal-nav">
            <a href="index.php?page=home" class="<?php echo $page==='home' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a>
            <a href="index.php?page=register" class="<?php echo $page==='register' ? 'active' : ''; ?>"><i class="fas fa-user-plus"></i> Register</a>
            <a href="index.php?page=login" class="<?php echo $page==='login' ? 'active' : ''; ?>"><i class="fas fa-sign-in-alt"></i> Login</a>
            
        </nav>
    </section>

    <main class="main-content">
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php
        switch ($page) {
            case 'home':
                include 'pages/home.php';
                break;
            case 'register':
                include 'pages/register.php';
                break;
            case 'login':
                include 'pages/login.php';
                break;
            case 'about':
                include 'pages/about.php';
                break;
            default:
                include 'pages/home.php';
        }
        ?>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>
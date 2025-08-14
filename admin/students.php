<?php
require_once '../config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

requireAdmin();

$db = Database::getInstance();

// Fetch all students
$students = $db->fetchAll("SELECT * FROM students ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>
body {
    background: linear-gradient(120deg, #ede9fe 0%, #f8fafc 100%);
    min-height: 100vh;
}
.admin-dashboard-flex {
    display: flex;
    min-height: 100vh;
}
.admin-sidebar {
    width: 220px;
    background: linear-gradient(180deg, #3b2066 0%, #a78bfa 100%);
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem 0 1rem 0;
    box-shadow: 2px 0 16px rgba(59,32,102,0.08);
    z-index: 2;
}
.admin-sidebar h2 {
    font-size: 1.5rem;
    margin-bottom: 2rem;
    font-weight: 800;
    letter-spacing: 1px;
}
.admin-sidebar a {
    color: #fff;
    text-decoration: none;
    margin: 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 500;
    padding: 0.5rem 1.2rem;
    border-radius: 8px;
    transition: background 0.2s;
    display: block;
}
.admin-sidebar a.active, .admin-sidebar a:hover {
    background: rgba(255,255,255,0.12);
}
.admin-main {
    flex: 1;
    padding: 2.5rem 3vw 2.5rem 3vw;
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
}
.admin-header {
    text-align: left;
    margin-bottom: 1.5rem;
}
.admin-header h1 {
    font-size: 2.5rem;
    font-weight: 800;
    background: linear-gradient(90deg,#ffffff,#ffffff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0.5rem;
}
.admin-header p {
    font-size: 1.1rem;
    color: #918b99ff;
    font-weight: 600;
}
.admin-card {
    background: rgba(255,255,255,0.85);
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(59,32,102,0.08);
    margin-bottom: 2rem;
    display: flex;
    flex-direction: column;
}
.admin-card-header {
    background: linear-gradient(90deg,#3b2066,#a78bfa);
    color: #fff;
    border-radius: 18px 18px 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.2rem 1.5rem;
}
.admin-card-header h2 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 700;
}
.admin-card-body {
    padding: 1.5rem;
    flex: 1;
}
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}
.table thead {
    background: #ede9fe;
}
.table th, .table td {
    padding: 12px 10px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.table th {
    font-weight: 700;
    color: #3b2066;
}
.empty-state {
    text-align: center;
    color: #aaa;
    padding: 2rem 0;
}
.badge {
    display: inline-block;
    padding: 0.35em 0.8em;
    font-size: 0.95em;
    font-weight: 600;
    border-radius: 12px;
    color: #fff;
}
.badge-success { background: #10b981; }
.badge-warning { background: #f59e0b; }
.badge-danger { background: #ef4444; }
@media (max-width: 900px) {
    .admin-dashboard-flex { flex-direction: column; }
    .admin-sidebar { width: 100%; flex-direction: row; justify-content: center; padding: 1rem 0; }
    .admin-main { padding: 1.5rem 2vw; }
}
</style>
<div class="admin-dashboard-flex">
    <nav class="admin-sidebar">
        <h2>Admin</h2>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="students.php" class="active"><i class="fas fa-users"></i> Students</a>
        <a href="subjects.php"><i class="fas fa-book"></i> Subjects</a>
        <a href="enrollment.php"><i class="fas fa-clipboard-list"></i> Enrollments</a>
        <a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a>
        <a href="payment.php"><i class="fas fa-money-bill"></i> Payments</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
    <main class="admin-main">
        <div class="admin-header">
            <h1>Manage Students</h1>
        </div>
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-users"></i> All Students</h2>
            </div>
            <div class="admin-card-body">
                <?php if (empty($students)): ?>
                    <div class="empty-state">
                        <i class="fas fa-user-slash"></i>
                        <p>No students found.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Applied Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                <tr>
                                    <td class="font-bold"><?php echo htmlspecialchars($student['student_id']); ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['course']); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td>
                                        <?php if ($student['status'] === 'approved'): ?>
                                            <span class="badge badge-success">Approved</span>
                                        <?php elseif ($student['status'] === 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger"><?php echo htmlspecialchars(ucfirst($student['status'])); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo formatDate($student['created_at']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
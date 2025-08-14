<?php
require_once '../config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

requireAdmin();

$db = Database::getInstance();
$admin = getCurrentAdmin();

// Get statistics
$stats = [
    'total_students' => $db->fetch("SELECT COUNT(*) as count FROM students")['count'],
    'pending_students' => $db->fetch("SELECT COUNT(*) as count FROM students WHERE status = 'pending'")['count'],
    'approved_students' => $db->fetch("SELECT COUNT(*) as count FROM students WHERE status = 'approved'")['count'],
    'total_enrollments' => $db->fetch("SELECT COUNT(*) as count FROM enrollments")['count'],
    'total_subjects' => $db->fetch("SELECT COUNT(*) as count FROM subjects")['count'],
    'total_revenue' => $db->fetch("SELECT COALESCE(SUM(total_amount), 0) as total FROM billing WHERE payment_status = 'paid'")['total']
];

// Get pending applications
$pendingStudents = $db->fetchAll("SELECT * FROM students WHERE status = 'pending' ORDER BY created_at DESC LIMIT 10");

// Get recent activities
$recentActivities = $db->fetchAll("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
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
    color: #fff;
    margin-bottom: 0.5rem;
    }
    .admin-header p {
        font-size: 1.5rem;
        color: #ffffffff;
        font-weight: 600;
    }
    .unique-stats-row {
        display: flex;
        gap: 2rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        margin-bottom: 2.5rem;
    }
    .unique-stat-glass {
        min-width: 180px;
        background: rgba(255,255,255,0.7);
        border-radius: 22px;
        box-shadow: 0 4px 24px rgba(59,32,102,0.10);
        padding: 1.5rem 1.2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        border: 2px solid #ede9fe;
        transition: box-shadow 0.3s, border 0.3s;
    }
    .unique-stat-glass i {
        font-size: 2.2rem;
        margin-bottom: 0.7rem;
        color: #3b2066;
    }
    .unique-stat-label {
        font-size: 1.1rem;
        color: #3b2066;
        font-weight: 600;
        margin-bottom: 0.2rem;
    }
    .unique-stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #18181b;
    }
    .admin-content-flex {
        display: flex;
        gap: 2.5rem;
        flex-wrap: wrap;
    }
    .admin-card {
        flex: 2 1 400px;
        min-width: 340px;
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
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    .empty-state {
        text-align: center;
        color: #aaa;
        padding: 2rem 0;
    }
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
    }
    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }
    .activity-icon {
        color: #a78bfa;
        font-size: 1.2rem;
        margin-top: 2px;
    }
    .activity-content p {
        margin: 0;
        color: #333;
        font-size: 1rem;
        font-weight: 500;
    }
    .activity-content small {
        color: #888;
        font-size: 0.95rem;
    }
    @media (max-width: 900px) {
        .admin-dashboard-flex { flex-direction: column; }
        .admin-sidebar { width: 100%; flex-direction: row; justify-content: center; padding: 1rem 0; }
        .admin-main { padding: 1.5rem 2vw; }
        .admin-content-flex { flex-direction: column; }
    }
    </style>

    <div class="admin-dashboard-flex">
        <nav class="admin-sidebar">
            <h2>Admin        </h2>
            <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="students.php"><i class="fas fa-users"></i> Students</a>
            <a href="subjects.php"><i class="fas fa-book"></i> Subjects</a>
            <a href="enrollment.php"><i class="fas fa-clipboard-list"></i> Enrollments</a>
            <a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a>
            <a href="payment.php"><i class="fas fa-money-bill"></i> Payments</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
        <main class="admin-main">
            <div class="admin-header">
                <h1>Admin Dashboard</h1>
                <p>Welcome, <?php echo htmlspecialchars($admin['username']); ?> (<?php echo ucfirst($admin['role']); ?>)</p>
            </div>
            <div class="unique-stats-row">
                <div class="unique-stat-glass">
                    <i class="fas fa-users"></i>
                    <div class="unique-stat-label">Total Students</div>
                    <div class="unique-stat-value"><?php echo $stats['total_students']; ?></div>
                </div>
                <div class="unique-stat-glass">
                    <i class="fas fa-clock"></i>
                    <div class="unique-stat-label">Pending</div>
                    <div class="unique-stat-value"><?php echo $stats['pending_students']; ?></div>
                </div>
                <div class="unique-stat-glass">
                    <i class="fas fa-check"></i>
                    <div class="unique-stat-label">Approved</div>
                    <div class="unique-stat-value"><?php echo $stats['approved_students']; ?></div>
                </div>
                <div class="unique-stat-glass">
                    <i class="fas fa-book"></i>
                    <div class="unique-stat-label">Enrollments</div>
                    <div class="unique-stat-value"><?php echo $stats['total_enrollments']; ?></div>
                </div>
                <div class="unique-stat-glass">
                    <i class="fas fa-graduation-cap"></i>
                    <div class="unique-stat-label">Subjects</div>
                    <div class="unique-stat-value"><?php echo $stats['total_subjects']; ?></div>
                </div>
                <div class="unique-stat-glass">
                    <i class="fas fa-money-bill"></i>
                    <div class="unique-stat-label">Revenue</div>
                    <div class="unique-stat-value"><?php echo formatCurrency($stats['total_revenue']); ?></div>
                </div>
            </div>
            <div class="admin-content-flex">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2><i class="fas fa-user-clock"></i> Pending Applications</h2>
                        <a href="students.php" class="btn btn-primary btn-sm">Manage All</a>
                    </div>
                    <div class="admin-card-body">
                        <?php if (empty($pendingStudents)): ?>
                            <div class="empty-state">
                                <i class="fas fa-check-circle"></i>
                                <p>No pending applications</p>
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
                                            <th>Applied Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pendingStudents as $student): ?>
                                        <tr>
                                            <td class="font-bold"><?php echo htmlspecialchars($student['student_id']); ?></td>
                                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                                            <td><?php echo htmlspecialchars($student['course']); ?></td>
                                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                                            <td><?php echo formatDate($student['created_at']); ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-success btn-sm" onclick="approveStudent(<?php echo $student['id']; ?>)">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="rejectStudent(<?php echo $student['id']; ?>)">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="admin-card" style="flex:1 1 300px; min-width:300px;">
                    <div class="admin-card-header">
                        <h2><i class="fas fa-history"></i> Recent Activities</h2>
                    </div>
                    <div class="admin-card-body">
                        <div class="activity-list">
                            <?php foreach ($recentActivities as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <p><?php echo htmlspecialchars($activity['message']); ?></p>
                                    <small><?php echo formatDate($activity['created_at'], 'M d, Y g:i A'); ?></small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
    <script>
    function approveStudent(id) {
        if (confirm('Are you sure you want to approve this student?')) {
            updateStudentStatus(id, 'approve');
        }
    }
    function rejectStudent(id) {
        if (confirm('Are you sure you want to reject this student?')) {
            updateStudentStatus(id, 'reject');
        }
    }
    function updateStudentStatus(id, action) {
        fetch('approve_reject_student.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + encodeURIComponent(id) + '&action=' + encodeURIComponent(action)
        })
        .then(response => response.text())
        .then(result => {
            if (result.trim() === 'success') {
                location.reload();
            } else {
                alert('Failed to update student status.');
            }
        });
    }
    </script>
</body>
</html>
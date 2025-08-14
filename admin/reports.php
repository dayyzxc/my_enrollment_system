<?php
require_once '../config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

requireAdmin();

$db = Database::getInstance();

// Example: Get summary statistics for reports
$totalStudents = $db->fetch("SELECT COUNT(*) as count FROM students")['count'];
$totalEnrollments = $db->fetch("SELECT COUNT(*) as count FROM enrollments")['count'];
$totalSubjects = $db->fetch("SELECT COUNT(*) as count FROM subjects")['count'];
$totalRevenue = $db->fetch("SELECT COALESCE(SUM(total_amount), 0) as total FROM billing WHERE payment_status = 'paid'")['total'];

// Example: Get monthly enrollment counts
$monthlyEnrollments = $db->fetchAll("
    SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count
    FROM enrollments
    GROUP BY month
    ORDER BY month DESC
    LIMIT 12
");

// Example: Get top 5 subjects by enrollment
$topSubjects = $db->fetchAll("
    SELECT sub.title AS subject_name, COUNT(e.id) as enroll_count
    FROM enrollments e
    LEFT JOIN subjects sub ON e.subject_id = sub.id
    GROUP BY e.subject_id
    ORDER BY enroll_count DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports - <?php echo APP_NAME; ?></title>
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
    color: #3b2066;
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
@media (max-width: 900px) {
    .admin-dashboard-flex { flex-direction: column; }
    .admin-sidebar { width: 100%; flex-direction: row; justify-content: center; padding: 1rem 0; }
    .admin-main { padding: 1.5rem 2vw; }
}
</style>
<div class="admin-dashboard-flex">
    <nav class="admin-sidebar">
        <h2>Admin      </h2>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="students.php"><i class="fas fa-users"></i> Students</a>
        <a href="subjects.php"><i class="fas fa-book"></i> Subjects</a>
        <a href="enrollment.php"><i class="fas fa-clipboard-list"></i> Enrollments</a>
        <a href="reports.php" class="active"><i class="fas fa-chart-bar"></i> Reports</a>
        <a href="payment.php"><i class="fas fa-money-bill"></i> Payments</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
    <main class="admin-main">
        <div class="admin-header">
            <h1>Reports & Analytics</h1>
        </div>
        <div class="unique-stats-row">
            <div class="unique-stat-glass">
                <i class="fas fa-users"></i>
                <div class="unique-stat-label">Total Students</div>
                <div class="unique-stat-value"><?php echo $totalStudents; ?></div>
            </div>
            <div class="unique-stat-glass">
                <i class="fas fa-book"></i>
                <div class="unique-stat-label">Total Enrollments</div>
                <div class="unique-stat-value"><?php echo $totalEnrollments; ?></div>
            </div>
            <div class="unique-stat-glass">
                <i class="fas fa-graduation-cap"></i>
                <div class="unique-stat-label">Total Subjects</div>
                <div class="unique-stat-value"><?php echo $totalSubjects; ?></div>
            </div>
            <div class="unique-stat-glass">
                <i class="fas fa-money-bill"></i>
                <div class="unique-stat-label">Total Revenue</div>
                <div class="unique-stat-value"><?php echo formatCurrency($totalRevenue); ?></div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-chart-line"></i> Monthly Enrollments (Last 12 Months)</h2>
            </div>
            <div class="admin-card-body">
                <?php if (empty($monthlyEnrollments)): ?>
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <p>No enrollment data found.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Enrollments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($monthlyEnrollments as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['month']); ?></td>
                                    <td><?php echo htmlspecialchars($row['count']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-star"></i> Top 5 Subjects by Enrollment</h2>
            </div>
            <div class="admin-card-body">
                <?php if (empty($topSubjects)): ?>
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <p>No subject data found.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>Enrollments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topSubjects as $subject): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                    <td><?php echo htmlspecialchars($subject['title']); ?></td>
                                    <td><?php echo htmlspecialchars($subject['description'] ?? ''); ?></td>
                                    <td><?php echo formatDate($subject['created_at']); ?></td>
                                    <td><?php echo htmlspecialchars($subject['enroll_count']); ?></td>
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
<script src="../assets/js/script.js"></script>

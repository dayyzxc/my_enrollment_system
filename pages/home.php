<?php
// ========================= pages/home.php =========================
?>
<div class="container">
    <div class="form-header text-center">
        <h1 class="form-title">Welcome to NCST Enrollment System</h1>
        <p class="form-subtitle">Manage your academic journey with our comprehensive enrollment platform</p>
    </div>

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin: 2rem 0;">
        <div class="card text-center">
            <div class="card-body">
                <div class="feature-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>Student Registration</h3>
                <p>Create a new student account and start your enrollment process with easy online registration.</p>
                <a href="?page=register" class="btn btn-primary">Get Started</a>
            </div>
        </div>
        

        <div class="card text-center">
            <div class="card-body">
                <div class="feature-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3>Course Management</h3>
                <p>Browse available courses, view schedules, and enroll in subjects that fit your academic plan.</p>
                <a href="?page=login" class="btn btn-primary">View Courses</a>
            </div>
        </div>

        <div class="card text-center">
            <div class="card-body">
                <div class="feature-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <h3>Admin Dashboard</h3>
                <p>Administrative tools for managing student applications, courses, and system settings.</p>
                <a href="admin/login.php" class="btn btn-primary">Admin Login</a>
            </div>
        </div>

         <div class="card text-center">
            <div class="card-body">
                <div class="">
                    <i class=""></i>
                </div>
                <h3></h3>
                <p></p>
                <a href="" class=""></a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-info-circle"></i> Academic Features</h2>
        </div>
        <div class="card-body">
            <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <h4><i class="fas fa-shield-alt text-primary"></i> Advanced Learning Programs</h4>
                    <p>STEM, Arts, Language immersion programs.</p>
                </div>
                <div>
                    <h4><i class="fas fa-mobile-alt text-primary"></i> Laboratories</h4>
                    <p>Science, computer, robotics labs.</p>
                </div>
                <div>
                    <h4><i class="fas fa-clock text-primary"></i> E-Learning Platforms</h4>
                    <p>Online resources, assignments, and virtual classes.</p>
                </div>
                <div>
                    <h4><i class="fas fa-chart-bar text-primary"></i> Qualified Teachers</h4>
                    <p>Certified, experienced, and continuously trained.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: var(--white);
}

.text-primary { color: var(--primary); }
</style>
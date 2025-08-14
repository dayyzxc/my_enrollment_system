<?php
// ========================= includes/header.php =========================
?>
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <img src="ncst.jpg" alt="Logo" style="height:2.2rem;width:auto;vertical-align:middle;border-radius:50%;margin-right:0.5rem;">
                <?php echo 'NCST ENROLLMENT'; ?>
            </div>
            <nav>
                <ul class="nav">
                    <li class="nav-item">
                        
                    </li>
                    <?php if (!isLoggedIn() && !isAdmin()): ?>
                    <li class="nav-item">
                        
                    </li>
                    <li class="nav-item">
                        
                    </li>
                    <?php endif; ?>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php $user = getCurrentUser(); ?>
                        <li class="nav-item">
                            <a href="student/dashboard.php" class="nav-link">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="student/enrollment.php" class="nav-link">
                                <i class="fas fa-book"></i> Enrollment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="student/profile.php" class="nav-link">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($user['name']); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if (isAdmin()): ?>
                        <?php $admin = getCurrentAdmin(); ?>
                        <li class="nav-item">
                            <a href="admin/dashboard.php" class="nav-link">
                                <i class="fas fa-cogs"></i> Admin Panel
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">
                                <i class="fas fa-sign-out-alt"></i> Logout (<?php echo htmlspecialchars($admin['username']); ?>)
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>
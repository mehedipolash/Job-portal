<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | TalentHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56e4;
            --secondary: #6c757d;
            --success: #4cc9f0;
            --dark: #1e1e2d;
            --darker: #14141f;
            --light: #f8f9fa;
            --card-bg: #252540;
            --card-hover: #2d2d4d;
            --border: #343456;
            --danger: #ff4d6d;
            --warning: #ffaa33;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, var(--darker), var(--dark));
            color: var(--light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header Styles */
        .header {
            background: rgba(30, 30, 45, 0.9);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--border);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo i {
            font-size: 1.8rem;
            color: var(--success);
        }
        
        .logo h1 {
            font-size: 1.5rem;
            font-weight: 600;
            background: linear-gradient(to right, var(--success), var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-greeting {
            text-align: right;
        }
        
        .user-greeting h2 {
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .user-greeting p {
            font-size: 0.9rem;
            color: var(--secondary);
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--success));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        /* Main Content */
        .container {
            display: flex;
            flex: 1;
        }
        
        /* Sidebar */
        .sidebar {
            width: 260px;
            background: rgba(30, 30, 45, 0.85);
            backdrop-filter: blur(5px);
            padding: 1.5rem 0;
            border-right: 1px solid var(--border);
            height: calc(100vh - 70px);
            position: sticky;
            top: 70px;
        }
        
        .nav-title {
            padding: 0.5rem 1.5rem;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: var(--secondary);
            letter-spacing: 1px;
            margin-top: 1.5rem;
        }
        
        .nav-links {
            list-style: none;
            padding: 0;
        }
        
        .nav-links li {
            margin: 5px 0;
        }
        
        .nav-links a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.85rem 1.5rem;
            color: var(--light);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            font-size: 0.95rem;
        }
        
        .nav-links a:hover {
            background: var(--card-bg);
        }
        
        .nav-links a.active {
            background: var(--primary);
            color: white;
        }
        
        .nav-links a.active:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--success);
        }
        
        .nav-links i {
            width: 24px;
            text-align: center;
        }
        
        /* Dashboard Content */
        .dashboard-content {
            flex: 1;
            padding: 2.5rem;
        }
        
        .welcome-section {
            margin-bottom: 2.5rem;
        }
        
        .welcome-section h1 {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, var(--light), var(--success));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .welcome-section p {
            color: #a0a0c0;
            max-width: 600px;
            line-height: 1.6;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        
        .stat-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, background 0.3s ease;
            border: 1px solid var(--border);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            background: var(--card-hover);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .icon-primary {
            background: rgba(67, 97, 238, 0.2);
            color: var(--primary);
        }
        
        .icon-success {
            background: rgba(76, 201, 240, 0.2);
            color: var(--success);
        }
        
        .icon-warning {
            background: rgba(255, 170, 51, 0.2);
            color: var(--warning);
        }
        
        .icon-danger {
            background: rgba(255, 77, 109, 0.2);
            color: var(--danger);
        }
        
        .stat-title {
            font-size: 0.9rem;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .stat-diff {
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .diff-up {
            color: #4ade80;
        }
        
        .diff-down {
            color: var(--danger);
        }
        
        /* Quick Actions */
        .section-title {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title i {
            color: var(--success);
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        
        .action-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.8rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--border);
        }
        
        .action-card:hover {
            background: var(--card-hover);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .action-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .action-card h3 {
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
        }
        
        .action-card p {
            color: #a0a0c0;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .action-btn {
            display: inline-block;
            padding: 0.7rem 1.8rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 200px;
        }
        
        .action-btn:hover {
            background: var(--primary-dark);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .logout-btn {
            background: transparent;
            border: 1px solid var(--danger);
            color: var(--danger);
        }
        
        .logout-btn:hover {
            background: var(--danger);
            color: white;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding: 1.5rem;
            background: rgba(30, 30, 45, 0.9);
            backdrop-filter: blur(5px);
            border-top: 1px solid var(--border);
            color: var(--secondary);
            font-size: 0.9rem;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
            }
            
            .nav-links {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .nav-links li {
                flex: 1 0 200px;
            }
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                padding: 1rem;
            }
            
            .dashboard-content {
                padding: 1.5rem;
            }
            
            .stats-grid,
            .actions-grid {
                grid-template-columns: 1fr;
            }
            
            .nav-links li {
                flex: 1 0 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <i class="fas fa-crown"></i>
            <h1>TalentHub Admin</h1>
        </div>
        <div class="user-info">
            <div class="user-greeting">
                <h2><?php echo htmlspecialchars($_SESSION['name']); ?></h2>
                <p>Administrator</p>
            </div>
            <div class="user-avatar">
                <?php echo strtoupper(substr(htmlspecialchars($_SESSION['name']), 0, 1)); ?>
            </div>
        </div>
    </header>
    
    <div class="container">
        <!-- Sidebar Navigation -->
        <nav class="sidebar">
            <div class="nav-title">Main Navigation</div>
            <ul class="nav-links">
                <li>
                    <a href="#" class="active">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="../edit-profile.php">
                        <i class="fas fa-user-edit"></i>
                        Edit Profile
                    </a>
                </li>
                <li>
                    <a href="../post-job.php">
                        <i class="fas fa-briefcase"></i>
                        Post a Job
                    </a>
                </li>
                <li>
                    <a href="view-applicants.php">
                        <i class="fas fa-users"></i>
                        View Applicants
                    </a>
                </li>
                <li>
                    <a href="../view-jobs.php">
                        <i class="fas fa-list"></i>
                        View All Jobs
                    </a>
                </li>
                <li>
                    <a href="../logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Main Dashboard Content -->
        <main class="dashboard-content">
            <div class="welcome-section">
                <h1>Welcome Back, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
                <p>You have full control over job postings, applications, and user management. Monitor key metrics and take quick actions from your dashboard.</p>
            </div>
            
        
            
            <!-- Quick Actions -->
            <h2 class="section-title">
                <i class="fas fa-bolt"></i>
                Quick Actions
            </h2>
            
            <div class="actions-grid">
                <div class="action-card">
                    <div class="action-icon icon-primary">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <h3>Edit Profile</h3>
                    <p>Update your personal information, profile picture, and security settings.</p>
                    <a href="../edit-profile.php" class="action-btn">Edit Profile</a>
                </div>
                
                <div class="action-card">
                    <div class="action-icon icon-success">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3>Post a New Job</h3>
                    <p>Create a new job listing to attract qualified candidates to your organization.</p>
                    <a href="../post-job.php" class="action-btn">Post Job</a>
                </div>
                
                <div class="action-card">
                    <div class="action-icon icon-warning">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>View Applicants</h3>
                    <p>Review and manage all job applicants in one centralized location.</p>
                    <a href="view-applicants.php" class="action-btn">View Applicants</a>
                </div>
                
                <div class="action-card">
                    <div class="action-icon icon-danger">
                        <i class="fas fa-list"></i>
                    </div>
                    <h3>Manage Job Listings</h3>
                    <p>View, edit, or remove existing job postings on your platform.</p>
                    <a href="../view-jobs.php" class="action-btn">View Jobs</a>
                </div>
            </div>
        </main>
    </div>
    
    <footer class="footer">
        <p>&copy; 2025  Admin Dashboard. All rights reserved by MD Mehedi Hasan Polash <i class="fas fa-heart" style="color: var(--danger);"></i></p>
    </footer>
</body>
</html>
<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'premium') {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1a2a6c, #2c3e50);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #333;
        }

        .dashboard-container {
            width: 100%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 80vh;
        }

        .dashboard-header {
            background: linear-gradient(to right, #4e89ff, #8a2be2);
            color: white;
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .user-details h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .user-details p {
            font-size: 16px;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-details p i {
            color: #ffd700;
        }

        .premium-badge {
            background: rgba(255, 215, 0, 0.2);
            border: 1px solid #ffd700;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 14px;
            font-weight: 600;
            color: #ffd700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .dashboard-content {
            display: flex;
            flex: 1;
        }

        .dashboard-nav {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 30px 20px;
        }

        .nav-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4e89ff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            text-decoration: none;
            color: #e0e0e0;
            border-radius: 10px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav-links a:hover, .nav-links a.active {
            background: rgba(78, 137, 255, 0.2);
            color: white;
            transform: translateX(5px);
        }

        .nav-links a i {
            width: 24px;
            text-align: center;
            font-size: 18px;
        }

        .main-content {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .welcome-section {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .welcome-section h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 24px;
        }

        .welcome-section p {
            color: #555;
            line-height: 1.6;
            max-width: 800px;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            gap: 15px;
            transition: transform 0.3s;
            border-left: 5px solid #4e89ff;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 32px;
            color: #4e89ff;
        }

        .stat-card h3 {
            font-size: 18px;
            color: #555;
            font-weight: 600;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
        }

        .dashboard-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 10px;
        }

        .action-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 20px;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .action-card:hover {
            border-color: #4e89ff;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(78, 137, 255, 0.2);
        }

        .action-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(to right, #4e89ff, #8a2be2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
        }

        .action-card h3 {
            font-size: 20px;
            color: #2c3e50;
            font-weight: 600;
        }

        .action-card p {
            color: #666;
            font-size: 15px;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(to right, #4e89ff, #8a2be2);
            color: white;
            padding: 14px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(78, 137, 255, 0.3);
            width: 100%;
            max-width: 250px;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(78, 137, 255, 0.4);
        }

        .logout-btn {
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            margin-top: 30px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 14px;
            border-top: 1px solid #eee;
            margin-top: auto;
        }

        /* Responsive design */
        @media (max-width: 900px) {
            .dashboard-content {
                flex-direction: column;
            }
            
            .dashboard-nav {
                width: 100%;
            }
            
            .dashboard-header {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }
            
            .user-info {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
                    <p><span class="premium-badge"><i class="fas fa-crown"></i> Premium User</span></p>
                </div>
            </div>
            <div class="header-actions">
                <a href="#" class="btn"><i class="fas fa-bell"></i> Notifications</a>
            </div>
        </header>
        
        <div class="dashboard-content">
            <nav class="dashboard-nav">
                <h2 class="nav-title"><i class="fas fa-th-large"></i> Dashboard Menu</h2>
                <ul class="nav-links">
                    <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="../edit-profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
                    <li><a href="../post-job.php"><i class="fas fa-briefcase"></i> Post a Job</a></li>
                    <li><a href="../view-jobs.php"><i class="fas fa-search"></i> View Jobs</a></li>
                    <li><a href="applied_jobs.php"><i class="fas fa-file-alt"></i> My Applications</a></li>
                    <li><a href="#"><i class="fas fa-chart-line"></i> Analytics</a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                </ul>
                
                <a href="../logout.php" class="btn logout-btn">Logout</a>
            </nav>
            
            <main class="main-content">
                <section class="welcome-section">
                    <h2>Your Premium Dashboard</h2>
                    <p>Welcome to your exclusive job portal dashboard. As a premium member, you have access to advanced features including priority job listings, enhanced visibility for your applications, and detailed analytics to track your job search progress.</p>
                </section>
                
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <i class="fas fa-briefcase"></i>
                        <h3>Jobs Posted</h3>
                        <div class="stat-value">12</div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-file-alt"></i>
                        <h3>Applications Sent</h3>
                        <div class="stat-value">24</div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-eye"></i>
                        <h3>Profile Views</h3>
                        <div class="stat-value">186</div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-star"></i>
                        <h3>Success Rate</h3>
                        <div class="stat-value">85%</div>
                    </div>
                </div>
                
                <div class="dashboard-actions">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h3>Edit Profile</h3>
                        <p>Update your personal information, skills, and professional details</p>
                        <a href="../edit-profile.php" class="btn">Update Profile</a>
                    </div>
                    
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3>Post a Job</h3>
                        <p>Create a new job listing to find the perfect candidate for your position</p>
                        <a href="../post-job.php" class="btn">Create Job</a>
                    </div>
                    
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>View Jobs</h3>
                        <p>Browse available positions that match your skills and preferences</p>
                        <a href="../view-jobs.php" class="btn">Find Jobs</a>
                    </div>
                    
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>My Applications</h3>
                        <p>Track the status of all your job applications in one place</p>
                        <a href="applied_jobs.php" class="btn">View Applications</a>
                    </div>
                </div>
            </main>
        </div>
        
        <footer class="footer">
            <p>&copy; <?php echo date('Y'); ?> Job Portal Premium Dashboard. All rights reserved by MD MEHEDI HASAN POLASH.</p>
        </footer>
    </div>
</body>
</html>
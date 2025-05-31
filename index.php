<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Portal - Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="dark-mode">
    <div class="container">
        <h1>Welcome to the Job Portal</h1>
        <p>This platform helps connect job seekers with employers.</p>

        <div class="nav-buttons">
            <a href="register.php" class="btn">Register</a>
            <a href="login.php" class="btn">Login</a>
            <a href="view-jobs.php" class="btn">Browse Jobs</a>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
            <a href="logout.php" class="btn">Logout</a>
        <?php endif; ?>
    </div>
</body>
</html>

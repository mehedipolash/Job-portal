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
    <title>Premium User Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dark-mode">
    <div class="form-container">
        <h2>Premium User Dashboard</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>

        <div class="dashboard-links">
        <a href="../edit-profile.php" class="btn">Edit Profile</a>
            <a href="../post-job.php" class="btn">Post a Job</a>
            <a href="../view-jobs.php" class="btn">View Jobs</a>
            <a href="applied_jobs.php"><button>My Applications</button></a>

            <a href="../logout.php" class="btn logout">Logout</a>
        </div>
    </div>
</body>
</html>

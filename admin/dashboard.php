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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css" />
    <style>
        /* Basic styling in case your style.css doesn't style links/buttons */
        .form-container {
            max-width: 600px;
            margin: 3rem auto;
            background-color: #222;
            padding: 2rem;
            border-radius: 8px;
            color: #eee;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .dashboard-links {
            margin-top: 2rem;
        }
        .dashboard-links a.btn {
            display: inline-block;
            margin: 0.5rem;
            padding: 0.75rem 1.5rem;
            background-color: #444;
            color: #eee;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .dashboard-links a.btn:hover {
            background-color: #666;
        }
        .dashboard-links a.logout {
            background-color: #900;
        }
        .dashboard-links a.logout:hover {
            background-color: #b00;
        }
    </style>
</head>
<body class="dark-mode">





    <main class="form-container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>

        <nav class="dashboard-links" aria-label="Admin Dashboard Navigation">
        <a href="../edit-profile.php" class="btn">Edit Profile</a>
            <a href="../post-job.php" class="btn">Post a Job</a>
            <a href="view-applicants.php" class="btn">View Applicants</a>
            <a href="../view-jobs.php" class="btn">View All Jobs</a>
           

            <a href="../logout.php" class="btn logout">Logout</a>
        </nav>
    </main>



    



</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'general') {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>General User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .dashboard-container {
            background: #ffffff10;
            backdrop-filter: blur(10px);
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .dashboard-container h2 {
            font-size: 2rem;
            color: #00ffe0;
            margin-bottom: 1rem;
        }

        .dashboard-container p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: #f0f0f0;
        }

        .dashboard-links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .btn, .dashboard-links a button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            background: linear-gradient(45deg, #00ffd5, #00b0ff);
            color: #000;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.3s;
            text-decoration: none;
        }

        .btn:hover, .dashboard-links a button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,255,213,0.3);
        }

        .logout {
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            color: white;
        }

        .logout:hover {
            box-shadow: 0 6px 15px rgba(255, 65, 108, 0.3);
        }

        .btn i {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2><i class="fa-solid fa-house-user"></i> General User Dashboard</h2>
        <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong>!</p>

        <div class="dashboard-links">
            <a href="../edit-profile.php" class="btn"><i class="fa-solid fa-user-pen"></i> Edit Profile</a>
            <a href="../view-jobs.php" class="btn"><i class="fa-solid fa-briefcase"></i> Browse Jobs</a>
            <a href="applied_jobs.php">
                <button class="btn"><i class="fa-solid fa-check-double"></i> My Applications</button>
            </a>
            <a href="../logout.php" class="btn logout"><i class="fa-solid fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</body>
</html>

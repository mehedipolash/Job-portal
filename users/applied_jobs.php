<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT j.title, j.company, j.location, j.salary, j.created_at
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    WHERE a.user_id = ?
    ORDER BY a.applied_at DESC
");
$stmt->execute([$user_id]);
$jobs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Applications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #121212;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #e0e0e0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background-color: #1e1e1e;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 255, 208, 0.1);
        }

        h2 {
            text-align: center;
            color: #00ffd0;
            margin-bottom: 30px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background-color: #2a2a2a;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 5px solid #00ffd0;
            border-radius: 8px;
            transition: 0.3s;
        }

        li:hover {
            background-color: #333333;
        }

        li strong {
            font-size: 1.2rem;
            color: #00ffd0;
        }

        li small {
            color: #aaa;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            text-align: center;
            text-decoration: none;
            color: #00ffd0;
            font-weight: bold;
            border: 1px solid #00ffd0;
            padding: 10px 20px;
            border-radius: 6px;
            transition: 0.3s ease;
        }

        .back-link:hover {
            background-color: #00ffd0;
            color: #000;
        }

        .no-jobs {
            text-align: center;
            font-size: 1.1rem;
            padding: 40px 0;
            color: #bbb;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fa-solid fa-clipboard-check"></i> Jobs You Applied For</h2>

        <?php if (count($jobs) > 0): ?>
            <ul>
                <?php foreach ($jobs as $job): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($job['title']); ?></strong><br>
                        <?php echo htmlspecialchars($job['company']) . ' - ' . htmlspecialchars($job['location']); ?><br>
                        <small>Salary: <?php echo htmlspecialchars($job['salary']); ?> | Posted on <?php echo date('F j, Y', strtotime($job['created_at'])); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-jobs"><i class="fa-solid fa-circle-info"></i> You haven’t applied to any jobs yet.</p>
        <?php endif; ?>

        <div style="text-align:center;">
            <a class="back-link" href="<?php echo $_SESSION['user_type'] == 'premium' ? 'premium_dashboard.php' : 'general_dashboard.php'; ?>">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>

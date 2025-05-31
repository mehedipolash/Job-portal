<?php
session_start();
include '../includes/db.php';

// Restrict access
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch applied jobs
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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dark-mode">
    <div class="form-container">
        <h2>Jobs You Applied For</h2>

        <?php if (count($jobs) > 0): ?>
            <ul>
                <?php foreach ($jobs as $job): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($job['title']); ?></strong><br>
                        <?php echo htmlspecialchars($job['company']) . ' - ' . htmlspecialchars($job['location']); ?><br>
                        <small>Salary: <?php echo htmlspecialchars($job['salary']); ?> | Posted on <?php echo $job['created_at']; ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You haven’t applied to any jobs yet.</p>
        <?php endif; ?>

        <p><a href="<?php echo $_SESSION['user_type'] == 'premium' ? 'premium_dashboard.php' : 'general_dashboard.php'; ?>">Back to Dashboard</a></p>
    </div>
</body>
</html>

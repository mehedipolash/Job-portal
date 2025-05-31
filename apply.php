<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['general', 'premium'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = (int) $_POST['job_id'];
    $user_id = $_SESSION['user_id'];

    // Check if already applied
    $check = $pdo->prepare("SELECT * FROM applications WHERE job_id = ? AND user_id = ?");
    $check->execute([$job_id, $user_id]);

    if ($check->rowCount() > 0) {
        $_SESSION['message'] = "You have already applied to this job.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO applications (job_id, user_id) VALUES (?, ?)");
        if ($stmt->execute([$job_id, $user_id])) {
            $_SESSION['message'] = "Application submitted successfully!";
        } else {
            $_SESSION['message'] = "Failed to apply. Try again.";
        }
    }

    header("Location: view-jobs.php");
    exit;
}
?>

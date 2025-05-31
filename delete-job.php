<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['admin', 'premium'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view-jobs.php");
    exit;
}

$job_id = (int)$_GET['id'];

// Fetch job and check ownership or admin rights
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->execute([$job_id]);
$job = $stmt->fetch();

if (!$job || ($job['posted_by'] != $user_id && $_SESSION['user_type'] !== 'admin')) {
    die("You do not have permission to delete this job.");
}

// Delete job
$stmt = $pdo->prepare("DELETE FROM jobs WHERE id = ?");
if ($stmt->execute([$job_id])) {
    header("Location: view-jobs.php?msg=Job+deleted+successfully");
    exit;
} else {
    die("Failed to delete job.");
}
?>

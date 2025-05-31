<?php
session_start();
include 'includes/db.php';

// Only admin and premium users allowed
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['admin', 'premium'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Get job ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view-jobs.php");
    exit;
}
$job_id = (int)$_GET['id'];

// Fetch job and verify ownership or admin
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->execute([$job_id]);
$job = $stmt->fetch();

if (!$job || ($job['posted_by'] != $user_id && $_SESSION['user_type'] !== 'admin')) {
    die("You do not have permission to edit this job.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $company = trim($_POST['company']);
    $location = trim($_POST['location']);
    $salary = trim($_POST['salary']);

    if (empty($title) || empty($description) || empty($company) || empty($location)) {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = $pdo->prepare("UPDATE jobs SET title = ?, description = ?, company = ?, location = ?, salary = ? WHERE id = ?");
        if ($stmt->execute([$title, $description, $company, $location, $salary, $job_id])) {
            $success = "Job updated successfully!";
            // Refresh job data
            $stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ?");
            $stmt->execute([$job_id]);
            $job = $stmt->fetch();
        } else {
            $error = "Failed to update job.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Job</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body class="dark-mode">
    <div class="form-container">
        <h2>Edit Job</h2>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="title">Job Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($job['title']); ?>" required>

            <label for="description">Job Description:</label>
            <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($job['description']); ?></textarea>

            <label for="company">Company:</label>
            <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($job['company']); ?>" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" required>

            <label for="salary">Salary:</label>
            <input type="text" id="salary" name="salary" value="<?php echo htmlspecialchars($job['salary']); ?>">

            <button type="submit">Update Job</button>
        </form>

        <p><a href="view-jobs.php">Back to Jobs</a></p>
    </div>
</body>
</html>

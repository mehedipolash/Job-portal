<?php
session_start();
include 'includes/db.php';

// Only allow logged-in users who are admin or premium
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['admin', 'premium'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $company = trim($_POST['company']);
    $location = trim($_POST['location']);
    $posted_by = $_SESSION['user_id'];

    if (empty($title) || empty($description) || empty($company) || empty($location)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO jobs (title, description, company, location, posted_by) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $description, $company, $location, $posted_by])) {
            $success = "Job posted successfully!";
        } else {
            $error = "Failed to post job.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post a Job</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="dark-mode">
    <div class="form-container">
        <h2>Post a Job</h2>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="title">Job Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Job Description:</label>
            <textarea name="description" id="description" cols="55" rows="5" required></textarea>

            <label for="company">Company Name:</label>
            <input type="text" name="company" id="company" required>

            <label for="location">Location:</label>
            <input type="text" name="location" id="location" required>

            <button type="submit">Post Job</button>
        </form>

        <p><a href="<?php echo $_SESSION['user_type'] == 'admin' ? 'admin/dashboard.php' : 'users/premium_dashboard.php'; ?>">Back to Dashboard</a></p>
    </div>
</body>
</html>

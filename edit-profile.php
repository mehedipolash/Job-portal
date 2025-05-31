<?php
session_start();
include 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch current user info
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    // User not found (should not happen)
    $error = "User not found.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Basic validation
    if (empty($name) || empty($email)) {
        $error = "Name and email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $password_confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email is used by another user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $error = "Email is already taken by another user.";
        } else {
            // Update user info
            if (!empty($password)) {
                // Hash new password
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                $updated = $update_stmt->execute([$name, $email, $password_hash, $user_id]);
            } else {
                // Update without password
                $update_stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $updated = $update_stmt->execute([$name, $email, $user_id]);
            }

            if ($updated) {
                $success = "Profile updated successfully.";
                $_SESSION['name'] = $name; // Update session name
            } else {
                $error = "Failed to update profile.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body class="dark-mode">
    <div class="form-container">
        <h2>Edit Profile</h2>

        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif ($success): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" />

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" />

            <label for="password">New Password (leave blank to keep current):</label>
            <input type="password" id="password" name="password" />

            <label for="password_confirm">Confirm New Password:</label>
            <input type="password" id="password_confirm" name="password_confirm" />

            <button type="submit">Update Profile</button>
        </form>

        <p><a href="<?php echo ($_SESSION['user_type'] === 'admin') ? 'admin/dashboard.php' : 'users/general_dashboard.php'; ?>">Back to Dashboard</a></p>
    </div>
</body>
</html>

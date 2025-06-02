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
    $error = "User not found.";
}

// Fetch user type if not already in session
if (!isset($_SESSION['user_type'])) {
    $stmt = $pdo->prepare("SELECT user_type FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_type = $stmt->fetchColumn();
    $_SESSION['user_type'] = $user_type;
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
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $error = "Email is already taken by another user.";
        } else {
            if (!empty($password)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                $updated = $update_stmt->execute([$name, $email, $password_hash, $user_id]);
            } else {
                $update_stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $updated = $update_stmt->execute([$name, $email, $user_id]);
            }

            if ($updated) {
                $success = "Profile updated successfully.";
                $_SESSION['name'] = $name;
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a2a6c, #2c3e50);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #333;
        }
        
        .container {
            width: 100%;
            max-width: 480px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .header {
            background: #2c3e50;
            color: white;
            padding: 30px 30px 20px;
            text-align: center;
            position: relative;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 16px;
        }
        
        .avatar-container {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(45deg, #3498db, #1abc9c);
            border: 4px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: 600;
            color: white;
        }
        
        .form-container {
            padding: 50px 40px 40px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
            font-size: 15px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            background: #f9f9f9;
        }
        
        input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            background: white;
        }
        
        .password-note {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: 6px;
        }
        
        .button-group {
            margin-top: 30px;
        }
        
        button[type="submit"] {
            width: 100%;
            padding: 16px;
            background: linear-gradient(to right, #3498db, #1abc9c);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(26, 188, 156, 0.3);
        }
        
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(26, 188, 156, 0.4);
        }
        
        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #3498db;
            font-weight: 500;
            text-decoration: none;
            font-size: 15px;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 15px;
        }
        
        .error {
            background: #ffeeee;
            color: #e74c3c;
            border: 1px solid #fadbd8;
        }
        
        .success {
            background: #eafaf1;
            color: #27ae60;
            border: 1px solid #d4efdf;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-size: 14px;
            border-top: 1px solid #eee;
        }
        
        @media (max-width: 480px) {
            .form-container {
                padding: 40px 25px 30px;
            }
            
            .header {
                padding: 25px 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
           
            <h1>Edit Profile</h1>
            <p>Update your account information</p>
        </div>
        
        <div class="form-container">
            <?php if ($error): ?>
                <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif ($success): ?>
                <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-wrapper">
                        <input type="text" id="name" name="name" required 
                               value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" 
                               placeholder="Enter your full name">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" required 
                               value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" 
                               placeholder="Enter your email">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">New Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" 
                               placeholder="Enter new password">
                    </div>
                    <p class="password-note">Leave blank to keep current password</p>
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Confirm Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password_confirm" name="password_confirm" 
                               placeholder="Confirm your new password">
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="submit">Update Profile</button>
                </div>
            </form>
            
            <?php if (isset($_SESSION['user_type'])): ?>
                <a href="<?php echo $_SESSION['user_type'] == 'admin' ? 'admin/dashboard.php' : ($_SESSION['user_type'] == 'premium' ? 'users/premium_dashboard.php' : 'users/general_dashboard.php'); ?>">
 

                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <?php else: ?>
                <a href="dashboard.php" class="back-link">
                    ← Back to Dashboard
                </a>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            &copy; <?php echo date('Y'); ?> YourAppName. All rights reserved.
        </div>
    </div>
</body>
</html>
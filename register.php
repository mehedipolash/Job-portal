<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Job Portal</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="dark-mode">

  <div class="form-container">
    <h2>User Registration</h2>
    <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user_type = $_POST['user_type'];

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
          echo "<p class='error'>Email already registered.</p>";
        } else {
          $stmt = $pdo->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
          if ($stmt->execute([$name, $email, $password, $user_type])) {
            echo "<p class='success'>Registration successful! <a href='login.php'>Login here</a></p>";
          } else {
            echo "<p class='error'>Registration failed.</p>";
          }
        }
      }
    ?>

<form action="" method="POST">

<label for="name">Full Name:</label>
<input type="text" id="name" name="name" placeholder="Enter your full name" required>

<label for="email">Email Address:</label>
<input type="email" id="email" name="email" placeholder="Enter your email" required>

<label for="password">Password:</label>
<input type="password" id="password" name="password" placeholder="Enter your password" required>

<label for="user_type">User Type:</label>
<select id="user_type" name="user_type" required>
  <option value="general">General User</option>
  <option value="premium">Premium User</option>
</select>

<button type="submit">Register</button>
</form>

  </div>

</body>
</html>

<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Job Portal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      display: flex;
      min-height: 100vh;
      background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
      overflow: hidden;
    }

    .decoration-panel {
      flex: 1;
      background: rgba(0, 0, 0, 0.7);
      color: white;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .decoration-panel::before {
      content: "";
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      z-index: 0;
    }

    .content-wrapper {
      position: relative;
      z-index: 2;
      max-width: 600px;
    }

    .logo {
      display: flex;
      align-items: center;
      margin-bottom: 40px;
    }

    .logo i {
      font-size: 36px;
      color: #4e89ff;
      margin-right: 15px;
    }

    .logo h1 {
      font-size: 32px;
      font-weight: 700;
      background: linear-gradient(to right, #4e89ff, #8a2be2);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    .panel-title {
      font-size: 42px;
      font-weight: 700;
      margin-bottom: 20px;
      line-height: 1.2;
    }

    .panel-subtitle {
      font-size: 18px;
      margin-bottom: 30px;
      opacity: 0.9;
      max-width: 80%;
    }

    .features {
      list-style: none;
      margin: 30px 0;
    }

    .features li {
      padding: 12px 0;
      font-size: 18px;
      display: flex;
      align-items: center;
    }

    .features i {
      color: #4e89ff;
      font-size: 20px;
      margin-right: 15px;
      width: 30px;
    }

    .testimonial {
      background: rgba(255, 255, 255, 0.1);
      border-left: 4px solid #4e89ff;
      padding: 20px;
      border-radius: 0 8px 8px 0;
      margin-top: 30px;
    }

    .testimonial p {
      font-style: italic;
      margin-bottom: 10px;
    }

    .testimonial .author {
      font-weight: 600;
      color: #4e89ff;
    }

    .form-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      background: rgba(255, 255, 255, 0.95);
    }

    .form-wrapper {
      width: 100%;
      max-width: 500px;
      padding: 40px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
    }

    .form-container h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
      font-size: 28px;
      position: relative;
      padding-bottom: 15px;
    }

    .form-container h2::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: linear-gradient(to right, #4e89ff, #8a2be2);
      border-radius: 2px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .input-group {
      position: relative;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #2c3e50;
      font-size: 15px;
    }

    input, select {
      width: 100%;
      padding: 15px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
      background: #f8f9fa;
    }

    input:focus, select:focus {
      border-color: #4e89ff;
      outline: none;
      box-shadow: 0 0 0 3px rgba(78, 137, 255, 0.2);
      background: white;
    }

    button {
      background: linear-gradient(to right, #4e89ff, #8a2be2);
      color: white;
      border: none;
      padding: 16px;
      border-radius: 8px;
      font-size: 18px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
      box-shadow: 0 4px 15px rgba(78, 137, 255, 0.3);
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 7px 20px rgba(78, 137, 255, 0.4);
    }

    .login-link {
      text-align: center;
      margin-top: 25px;
      color: #2c3e50;
      font-size: 16px;
    }

    .login-link a {
      color: #4e89ff;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.2s;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    .success {
      background: #d4edda;
      color: #155724;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      text-align: center;
      border: 1px solid #c3e6cb;
    }

    .error {
      background: #f8d7da;
      color: #721c24;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      text-align: center;
      border: 1px solid #f5c6cb;
    }

    .input-icon {
      position: absolute;
      right: 15px;
      top: 40px;
      color: #7a7a7a;
    }

    /* Animation for decorative elements */
    .circle {
      position: absolute;
      border-radius: 50%;
      background: rgba(78, 137, 255, 0.1);
      z-index: 1;
    }

    .circle-1 {
      width: 300px;
      height: 300px;
      top: -100px;
      left: -100px;
    }

    .circle-2 {
      width: 200px;
      height: 200px;
      bottom: -80px;
      right: -80px;
      background: rgba(138, 43, 226, 0.1);
    }

    /* Responsive design */
    @media (max-width: 900px) {
      body {
        flex-direction: column;
      }
      
      .decoration-panel {
        padding: 30px 20px;
        text-align: center;
      }
      
      .panel-subtitle,
      .features {
        max-width: 100%;
        margin-left: auto;
        margin-right: auto;
      }
      
      .logo {
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <!-- Decorative circles -->
  <div class="circle circle-1"></div>
  <div class="circle circle-2"></div>
  
  <!-- Left decorative panel -->
  <div style="background-color: #155724;" class="decoration-panel">
    <div class="content-wrapper">
      <div class="logo">
        <i class="fas fa-briefcase"></i>
        <h1>JobPortal</h1>
      </div>
      
      <h2 class="panel-title">Find Your Dream Career</h2>
      <p class="panel-subtitle">Join thousands of companies and job seekers using our platform to connect talent with opportunity.</p>
      
      <ul class="features">
        <li><i class="fas fa-check-circle"></i> Access to thousands of job listings</li>
        <li><i class="fas fa-check-circle"></i> Personalized job recommendations</li>
        <li><i class="fas fa-check-circle"></i> One-click application process</li>
        <li><i class="fas fa-check-circle"></i> Career development resources</li>
      </ul>
      
      <div class="testimonial">
        <p>"JobPortal helped me find my perfect role in just 2 weeks. The process was seamless and efficient!"</p>
        <div class="author">- Sarah Johnson, Product Manager</div>
      </div>
    </div>
  </div>
  
  <!-- Form container (right side) -->
  <div style="background: linear-gradient(135deg, #8e2de2, #4a00e0)" class="form-container">
    <div class="form-wrapper">
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
        <div class="input-group">
          <label for="name">Full Name:</label>
          <input type="text" id="name" name="name" placeholder="Enter your full name" required>
          <i class="fas fa-user input-icon"></i>
        </div>
        
        <div class="input-group">
          <label for="email">Email Address:</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required>
          <i class="fas fa-envelope input-icon"></i>
        </div>
        
        <div class="input-group">
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required>
          <i class="fas fa-lock input-icon"></i>
        </div>
        
        <div class="input-group">
          <label for="user_type">User Type:</label>
          <select id="user_type" name="user_type" required>
            <option value="general">General User</option>
            <option value="premium">Premium User</option>
          </select>
          <i class="fas fa-user-tag input-icon"></i>
        </div>
        
        <button type="submit">Register Now</button>
      </form>
      
      <div class="login-link">
        Already have an account? <a href="login.php">Sign in</a>
      </div>
    </div>
  </div>
</body>
</html>
<?php
session_start();
include 'includes/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare and execute query
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verify user and password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['name'] = $user['name'];

        // Redirect based on user type
        if ($user['user_type'] == 'admin') {
            header("Location: admin/dashboard.php");
        } elseif ($user['user_type'] == 'premium') {
            header("Location: users/premium_dashboard.php");
        } else {
            header("Location: users/general_dashboard.php");
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Job Portal</title>
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
      animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
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
      gap: 25px;
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

    input {
      width: 100%;
      padding: 15px 15px 15px 45px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
      background: #f8f9fa;
    }

    input:focus {
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
      left: 15px;
      top: 40px;
      color: #7a7a7a;
      font-size: 20px;
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 40px;
      color: #7a7a7a;
      cursor: pointer;
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

    .social-login {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin: 20px 0;
    }

    .social-btn {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f0f2f5;
      color: #555;
      font-size: 18px;
      transition: all 0.3s;
      border: 1px solid #e0e0e0;
    }

    .social-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .social-btn.google { color: #DB4437; }
    .social-btn.facebook { color: #4267B2; }
    .social-btn.linkedin { color: #0077B5; }

    /* Or divider */
    .or-divider {
      display: flex;
      align-items: center;
      margin: 20px 0;
    }

    .or-divider::before,
    .or-divider::after {
      content: "";
      flex: 1;
      height: 1px;
      background: #e0e0e0;
    }

    .or-divider span {
      padding: 0 15px;
      color: #777;
      font-weight: 500;
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
  <div style="background-color:#2c3e50;" class="decoration-panel">
    <div class="content-wrapper">
      <div class="logo">
        <i class="fas fa-briefcase"></i>
        <h1>JobPortal</h1>
      </div>
      
      <h2 class="panel-title">Welcome Back to Your Career Journey</h2>
      <p class="panel-subtitle">Access your personalized dashboard to manage applications, save jobs, and track your career progress.</p>
      
      <ul class="features">
        <li><i class="fas fa-rocket"></i> Quick access to your job applications</li>
        <li><i class="fas fa-bell"></i> Personalized job alerts</li>
        <li><i class="fas fa-chart-line"></i> Career growth insights</li>
        <li><i class="fas fa-users"></i> Connect with industry professionals</li>
      </ul>
      
      <div class="testimonial">
        <p>"Since using JobPortal, I've advanced my career and landed my dream job. The platform is intuitive and effective!"</p>
        <div class="author">- Michael Chen, Software Engineer</div>
      </div>
    </div>
  </div>
  
  <!-- Form container (right side) -->
  <div style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e'); background-size: cover; background-position: center;" class="form-container">

    <div class="form-wrapper">
      <h2>Login to Your Account</h2>
      
      <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
      <?php endif; ?>
      
      <form method="POST" action="">
        <div class="input-group">
          <label for="email">Email Address:</label>
          <i class="fas fa-envelope input-icon"></i>
          <input type="email" name="email" id="email" placeholder="Enter your email" required>
        </div>
        
        <div class="input-group">
          <label for="password">Password:</label>
          <i class="fas fa-lock input-icon"></i>
          <input type="password" name="password" id="password" placeholder="Enter your password" required>
          <i class="fas fa-eye password-toggle" id="togglePassword"></i>
        </div>
        
        <div class="input-group">
          <a href="#" style="display: block; text-align: right; margin-top: 5px; color: #4e89ff; text-decoration: none;">
            Forgot Password?
          </a>
        </div>
        
        <button type="submit">Login</button>
      </form>
      
      <div class="or-divider">
        <span>OR</span>
      </div>
      
      <div class="social-login">
        <div class="social-btn google">
          <i class="fab fa-google"></i>
        </div>
        <div class="social-btn facebook">
          <i class="fab fa-facebook-f"></i>
        </div>
        <div class="social-btn linkedin">
          <i class="fab fa-linkedin-in"></i>
        </div>
      </div>
      
      <div class="login-link">
        Don't have an account? <a href="register.php">Register here</a>
      </div>
    </div>
  </div>

  <script>
    // Password visibility toggle
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    
    togglePassword.addEventListener('click', function() {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
    
    // Form animation
    document.addEventListener('DOMContentLoaded', function() {
      const formWrapper = document.querySelector('.form-wrapper');
      formWrapper.style.animation = 'fadeIn 0.6s ease-out';
    });
  </script>
</body>
</html>
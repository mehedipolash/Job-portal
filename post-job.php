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
            // Clear form on success
            $title = $description = $company = $location = '';
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            max-width: 700px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .header {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .header-content {
            position: relative;
            z-index: 2;
        }
        
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .header p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 17px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .icon-container {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(45deg, #3498db, #1abc9c);
            border: 4px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .form-container {
            padding: 50px 40px 40px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .full-width {
            grid-column: span 2;
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
        
        input, textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            background: #f9f9f9;
        }
        
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        input:focus, textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            background: white;
        }
        
        .button-group {
            margin-top: 20px;
            grid-column: span 2;
            display: flex;
            justify-content: center;
        }
        
        button[type="submit"] {
            padding: 16px 50px;
            background: linear-gradient(to right, #3498db, #1abc9c);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(26, 188, 156, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
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
            transition: all 0.3s;
        }
        
        .back-link:hover {
            text-decoration: underline;
            transform: translateX(-5px);
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 15px;
            grid-column: span 2;
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
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .full-width {
                grid-column: span 1;
            }
            
            .button-group {
                grid-column: span 1;
            }
            
            .form-container {
                padding: 40px 25px 30px;
            }
            
            .header {
                padding: 25px 20px;
            }
            
            .header h1 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            
            <div class="header-content">
                <h1>Post a New Job Opportunity</h1>
                <p>Reach qualified candidates by posting your job opening on our platform</p>
            </div>
        </div>
        
        <div class="form-container">
            <form method="POST" action="">
                <div class="form-grid">
                    <?php if ($error): ?>
                        <div class="alert error"><?php echo $error; ?></div>
                    <?php elseif ($success): ?>
                        <div class="alert success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <div class="form-group full-width">
                        <label for="title">Job Title *</label>
                        <div class="input-wrapper">
                            <input type="text" id="title" name="title" required 
                                   value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" 
                                   placeholder="Enter job title (e.g. Senior Web Developer)">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="company">Company Name *</label>
                        <div class="input-wrapper">
                            <input type="text" id="company" name="company" required 
                                   value="<?php echo isset($company) ? htmlspecialchars($company) : ''; ?>" 
                                   placeholder="Enter company name">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location *</label>
                        <div class="input-wrapper">
                            <input type="text" id="location" name="location" required 
                                   value="<?php echo isset($location) ? htmlspecialchars($location) : ''; ?>" 
                                   placeholder="Enter job location">
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="description">Job Description *</label>
                        <div class="input-wrapper">
                            <textarea id="description" name="description" required 
                                      placeholder="Describe the position, responsibilities, requirements, and benefits"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="button-group">
                        <button type="submit">
                            <i class="fas fa-paper-plane"></i> Post Job
                        </button>
                    </div>
                </div>
            </form>
            
            <a href="<?php echo $_SESSION['user_type'] == 'admin' ? 'admin/dashboard.php' : 'users/premium_dashboard.php'; ?>" 
               class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <div class="footer">
            &copy; <?php echo date('Y'); ?>  All rights reserved by MD MEHEDI HASAN POLASH.
        </div>
    </div>
    
    <script>
        // Simple animation for form elements on focus
        document.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
                this.parentElement.style.boxShadow = '0 5px 15px rgba(52, 152, 219, 0.2)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'none';
                this.parentElement.style.boxShadow = 'none';
            });
        });
    </script>
</body>
</html>
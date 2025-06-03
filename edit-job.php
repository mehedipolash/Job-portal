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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Posting</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        h1 {
            color: #2c3e50;
            font-size: 28px;
        }
        
        .back-link {
            display: inline-block;
            padding: 8px 16px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: background 0.3s;
        }
        
        .back-link:hover {
            background: #2980b9;
        }
        
        .notification {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-weight: 500;
        }
        
        .error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        
        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .required:after {
            content: " *";
            color: #e74c3c;
        }
        
        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        input:focus, textarea:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        button {
            display: inline-block;
            padding: 12px 25px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        button:hover {
            background: #2980b9;
        }
        
        .footer-links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            
            header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .back-link {
                margin-top: 15px;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body style="background: linear-gradient(to right, #2c3e50, #8e44ad);">
    <div class="container">
        <header>
            <h1>Edit Job Posting</h1>
            <a href="view-jobs.php" class="back-link">Back to Jobs</a>
        </header>
        
        <?php if ($error): ?>
            <div class="notification error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="notification success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="title" class="required">Job Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($job['title']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description" class="required">Job Description</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($job['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="company" class="required">Company</label>
                <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($job['company']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="location" class="required">Location</label>
                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="salary">Salary</label>
                <input type="text" id="salary" name="salary" value="<?php echo htmlspecialchars($job['salary']); ?>">
            </div>
            
            <button type="submit">Update Job</button>
        </form>
        
        <div class="footer-links">
            <a href="view-jobs.php">View All Jobs</a>
            
        </div>
    </div>
</body>
</html>
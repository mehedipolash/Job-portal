<?php
session_start();
include 'includes/db.php';

$user_id = $_SESSION['user_id'] ?? null;
$user_type = $_SESSION['user_type'] ?? null;

// Get search/filter values from GET request
$search_title = $_GET['title'] ?? '';
$search_location = $_GET['location'] ?? '';
$search_company = $_GET['company'] ?? '';

// Build SQL with filters using prepared statements to prevent SQL injection
$sql = "SELECT jobs.*, users.name AS poster_name 
        FROM jobs 
        LEFT JOIN users ON jobs.posted_by = users.id 
        WHERE 1=1 ";
$params = [];

// Add conditions if filters are not empty
if ($search_title !== '') {
    $sql .= " AND jobs.title LIKE ? ";
    $params[] = "%$search_title%";
}
if ($search_location !== '') {
    $sql .= " AND jobs.location LIKE ? ";
    $params[] = "%$search_location%";
}
if ($search_company !== '') {
    $sql .= " AND jobs.company LIKE ? ";
    $params[] = "%$search_company%";
}

$sql .= " ORDER BY jobs.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --dark: #121212;
            --darker: #0a0a0a;
            --light: #f8f9fa;
            --gray: #6c757d;
            --card-bg: #1e1e1e;
            --border: #333;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--darker);
            color: var(--light);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid var(--border);
            margin-bottom: 30px;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            color: var(--success);
        }

        .page-title {
            font-size: 32px;
            margin: 20px 0;
            color: var(--light);
            position: relative;
            display: inline-block;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }

        .search-form {
            background-color: var(--card-bg);
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .form-title {
            font-size: 18px;
            margin-bottom: 20px;
            color: var(--success);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .form-row input {
            flex: 1;
            min-width: 200px;
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background-color: var(--dark);
            color: var(--light);
            font-size: 16px;
        }

        .form-row input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        button, .btn-clear {
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        button {
            background-color: var(--primary);
            color: white;
        }

        button:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-clear {
            background-color: var(--gray);
            color: white;
            text-decoration: none;
        }

        .btn-clear:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .jobs-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .job-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
            border: 1px solid var(--border);
        }

        .job-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }

        .job-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
        }

        .job-title {
            font-size: 22px;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .job-company {
            font-size: 18px;
            font-weight: 600;
            color: var(--success);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .job-location {
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
        }

        .job-body {
            padding: 20px;
        }

        .job-description {
            margin-bottom: 20px;
            color: #ddd;
            line-height: 1.7;
        }

        .job-footer {
            padding: 15px 20px;
            background-color: rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .poster-info {
            font-size: 14px;
            color: var(--gray);
        }

        .job-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .apply-btn, .edit-btn, .delete-btn {
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .apply-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            cursor: pointer;
        }

        .apply-btn:hover {
            background-color: var(--secondary);
        }

        .edit-btn {
            background-color: #17a2b8;
            color: white;
        }

        .edit-btn:hover {
            background-color: #138496;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .no-jobs {
            background-color: var(--card-bg);
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            margin: 30px 0;
            color: var(--gray);
        }

        .no-jobs i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #6c757d;
        }

        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 30px;
            padding: 12px 25px;
            background-color: #343a40;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .back-home:hover {
            background-color: #23272b;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .form-actions button, 
            .form-actions .btn-clear {
                width: 100%;
            }
            
            .jobs-list {
                grid-template-columns: 1fr;
            }
            
            .job-footer {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .job-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body class="dark-mode">
<div class="container">
    <header>
        <div class="logo">
            <i class="fas fa-briefcase"></i>
            <span>JobPortal</span>
        </div>
    </header>

    <h1 class="page-title">Available Jobs</h1>

    <!-- Search/Filter Form -->
    <form method="GET" action="view-jobs.php" class="search-form">
        <h2 class="form-title"><i class="fas fa-search"></i> Find Your Dream Job</h2>
        <div class="form-row">
            <input type="text" name="title" placeholder="Job Title, Keywords" value="<?php echo htmlspecialchars($search_title); ?>">
            <input type="text" name="location" placeholder="City, State or Remote" value="<?php echo htmlspecialchars($search_location); ?>">
            <input type="text" name="company" placeholder="Company Name" value="<?php echo htmlspecialchars($search_company); ?>">
        </div>
        <div class="form-actions">
            <button type="submit">
                <i class="fas fa-search"></i> Search Jobs
            </button>
            <a href="view-jobs.php" class="btn-clear">
                <i class="fas fa-times"></i> Clear Filters
            </a>
        </div>
    </form>

    <?php if (count($jobs) === 0): ?>
        <div class="no-jobs">
            <i class="fas fa-file-search"></i>
            <h2>No Jobs Found</h2>
            <p>Try adjusting your search filters or check back later.</p>
        </div>
    <?php else: ?>
        <div class="jobs-list">
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <div class="job-header">
                        <h2 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h2>
                        <p class="job-company">
                            <i class="fas fa-building"></i>
                            <?php echo htmlspecialchars($job['company']); ?>
                        </p>
                        <p class="job-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($job['location'] ?? 'Location not specified'); ?>
                        </p>
                    </div>
                    
                    <div class="job-body">
                        <p class="job-description">
                            <?php echo nl2br(htmlspecialchars(mb_strimwidth($job['description'], 0, 200, '...'))); ?>
                        </p>
                    </div>
                    
                    <div class="job-footer">
                        <div class="poster-info">
                            <i class="fas fa-user"></i> 
                            <?php echo htmlspecialchars($job['poster_name'] ?? 'Unknown'); ?> 
                            <i class="fas fa-clock"></i> 
                            <?php echo date('M j, Y', strtotime($job['created_at'])); ?>
                        </div>
                        
                        <div class="job-actions">
                            <?php if ($user_id && in_array($user_type, ['general', 'premium'])): ?>
                                <form action="apply.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                    <button type="submit" class="apply-btn">
                                        <i class="fas fa-paper-plane"></i> Apply
                                    </button>
                                </form>
                            <?php endif; ?>

                            <?php if ($user_id && in_array($user_type, ['admin', 'premium']) && $job['posted_by'] == $user_id): ?>
                                <a href="edit-job.php?id=<?php echo $job['id']; ?>" class="edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete-job.php?id=<?php echo $job['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this job?');">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <p>
    <?php if (isset($_SESSION['user_type'])): ?>
        <a href="<?php echo $_SESSION['user_type'] == 'admin' ? 'admin/dashboard.php' : ($_SESSION['user_type'] == 'premium' ? 'users/premium_dashboard.php' : 'users/general_dashboard.php'); ?>">
        
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <?php else: ?>
                <a href="dashboard.php" class="back-link">
                    ← Back to Dashboard
                </a>
            <?php endif; ?>
    </p>
</div>

<script>
    // Simple animations for better UX
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to job cards when they come into view
        const jobCards = document.querySelectorAll('.job-card');
        jobCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 150 * index);
        });
        
        // Form validation
        const searchForm = document.querySelector('.search-form');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const inputs = this.querySelectorAll('input[type="text"]');
                let isEmpty = true;
                
                inputs.forEach(input => {
                    if (input.value.trim() !== '') {
                        isEmpty = false;
                    }
                });
                
                if (isEmpty) {
                    e.preventDefault();
                    alert('Please enter at least one search criteria');
                }
            });
        }
    });
</script>
</body>
</html>
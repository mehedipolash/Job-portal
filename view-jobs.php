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
    <title>Job Listings</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="dark-mode">
<div class="container">
    <h1>Available Jobs</h1>

    <!-- Search/Filter Form -->
    <form method="GET" action="view-jobs.php" class="search-form">
        <input type="text" name="title" placeholder="Job Title" value="<?php echo htmlspecialchars($search_title); ?>">
        <input type="text" name="location" placeholder="Location" value="<?php echo htmlspecialchars($search_location); ?>">
        <input type="text" name="company" placeholder="Company" value="<?php echo htmlspecialchars($search_company); ?>">
        <button type="submit">Search</button>
        <a href="view-jobs.php" class="btn-clear">Clear</a>
    </form>

    <?php if (count($jobs) === 0): ?>
        <p>No jobs found matching your criteria.</p>
    <?php else: ?>
        <?php foreach ($jobs as $job): ?>
            <div class="job-card">
                <h2><?php echo htmlspecialchars($job['title']); ?></h2>
                <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location'] ?? 'Not specified'); ?></p>
                <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                <p><small>Posted by: <?php echo htmlspecialchars($job['poster_name'] ?? 'Unknown'); ?> on <?php echo date('F j, Y, g:i a', strtotime($job['created_at'])); ?></small></p>

                <?php if ($user_id && in_array($user_type, ['general', 'premium'])): ?>
                    <form action="apply.php" method="POST" style="display:inline;">
                        <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                        <button type="submit">Apply</button>
                    </form>
                <?php endif; ?>

                <?php if ($user_id && in_array($user_type, ['admin', 'premium']) && $job['posted_by'] == $user_id): ?>
                    &nbsp;|&nbsp;
                    <a href="edit-job.php?id=<?php echo $job['id']; ?>">Edit</a>
                    &nbsp;|&nbsp;
                    <a href="delete-job.php?id=<?php echo $job['id']; ?>" onclick="return confirm('Are you sure you want to delete this job?');">Delete</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <p><a href="index.php">Back to Home</a></p>
</div>
</body>
</html>

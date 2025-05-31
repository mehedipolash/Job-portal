<?php
session_start();
include '../includes/db.php';

// Only allow admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch all applications with job and applicant info
$stmt = $pdo->query("
    SELECT 
        j.id AS job_id,
        j.title,
        u.name AS applicant_name,
        u.email AS applicant_email,
        a.applied_at
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    JOIN users u ON a.user_id = u.id
    ORDER BY a.applied_at DESC
");

$applications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Applicants - Admin</title>
    <link rel="stylesheet" href="../css/style.css" />
    <style>
        /* Quick table styles in case your style.css doesn't have it */
        table.applicants-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        table.applicants-table th, table.applicants-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table.applicants-table th {
            background-color: #333;
            color: #fff;
        }
        table.applicants-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #222;
            color: #eee;
            border-radius: 8px;
            font-family: Arial, sans-serif;
        }
        a.btn {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: #444;
            color: #eee;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        a.btn:hover {
            background-color: #666;
        }
    </style>
</head>
<body class="dark-mode">
    <main class="container" role="main">
        <h1>Applicants for Jobs</h1>

        <?php if (count($applications) === 0): ?>
            <p>No applications found.</p>
        <?php else: ?>
            <table class="applicants-table" aria-describedby="applicantsDesc">
                <caption id="applicantsDesc">List of all job applicants and their applied job details.</caption>
                <thead>
                    <tr>
                        <th scope="col">Job Title</th>
                        <th scope="col">Applicant Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Applied At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($app['title']); ?></td>
                            <td><?php echo htmlspecialchars($app['applicant_name']); ?></td>
                            <td><a href="mailto:<?php echo htmlspecialchars($app['applicant_email']); ?>"><?php echo htmlspecialchars($app['applicant_email']); ?></a></td>
                            <td><?php echo htmlspecialchars($app['applied_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="dashboard.php" class="btn" role="button">Back to Dashboard</a>
    </main>
</body>
</html>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            margin: 0;
            background-color: #121212;
            color: #f0f0f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 1000px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: #1f1f1f;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 255, 208, 0.1);
        }

        h1 {
            text-align: center;
            color: #00ffd0;
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
        }

        thead {
            background-color: #2d2d2d;
        }

        thead th {
            color: #00ffd0;
        }

        tbody tr {
            background-color: #252525;
            border-bottom: 1px solid #333;
        }

        tbody tr:nth-child(even) {
            background-color: #2b2b2b;
        }

        tbody a {
            color: #00ffd0;
            text-decoration: none;
        }

        tbody a:hover {
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            margin-top: 2rem;
            padding: 10px 20px;
            background-color: #00ffd0;
            color: #000;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #00cbb8;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tbody tr {
                margin-bottom: 1.5rem;
                border: 1px solid #333;
                padding: 1rem;
                border-radius: 8px;
            }

            tbody td {
                padding: 0.5rem 0;
            }

            tbody td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 4px;
                color: #00ffd0;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <h1><i class="fa-solid fa-users-line"></i> Job Applicants</h1>

        <?php if (count($applications) === 0): ?>
            <p>No applications found.</p>
        <?php else: ?>
            <table aria-describedby="applicantsDesc">
                <caption id="applicantsDesc" style="display:none;">List of all job applicants and their applied job details.</caption>
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Applicant Name</th>
                        <th>Email</th>
                        <th>Applied At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td data-label="Job Title"><?php echo htmlspecialchars($app['title']); ?></td>
                            <td data-label="Applicant Name"><?php echo htmlspecialchars($app['applicant_name']); ?></td>
                            <td data-label="Email">
                                <a href="mailto:<?php echo htmlspecialchars($app['applicant_email']); ?>">
                                    <?php echo htmlspecialchars($app['applicant_email']); ?>
                                </a>
                            </td>
                            <td data-label="Applied At"><?php echo htmlspecialchars($app['applied_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div style="text-align:center;">
            <a href="dashboard.php" class="btn"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </main>
</body>
</html>

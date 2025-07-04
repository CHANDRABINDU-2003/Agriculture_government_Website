<?php
session_start();
require_once 'db.php';

// Only allow access if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all feedback
$sql = "SELECT f.id, u.full_name, f.name, f.email, f.subject, f.message, f.created_at 
        FROM feedback f 
        JOIN users u ON f.user_id = u.id 
        ORDER BY f.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Feedback Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #2e7d32;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 16px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #2e7d32;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .back-button {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 18px;
            background-color: #388e3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #2e7d32;
        }
    </style>
</head>

<body>
    <h2>Feedback Reports</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User (Farmer)</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No feedback found.</p>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="back-button">&larr; Back to Dashboard</a>
</body>

</html>
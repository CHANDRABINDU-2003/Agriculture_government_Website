<?php
session_start();
require_once 'db.php';

// Check if user is logged in (adjust as needed)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch content from database to show on user dashboard
$sql = "SELECT title, body, created_at FROM content ORDER BY created_at DESC LIMIT 10";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .content-section {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .content-item {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        .content-item:last-child {
            border-bottom: none;
        }

        .content-title {
            font-weight: bold;
            font-size: 1.3rem;
            color: #2c3e50;
        }

        .content-body {
            margin: 10px 0;
            color: #555;
        }

        .content-date {
            font-size: 0.85rem;
            color: #999;
        }

        h2 {
            text-align: center;
            color: #34495e;
        }
    </style>
</head>

<body>

    <div class="content-section">
        <h2>Latest Updates & Tips</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="content-item">
                    <div class="content-title"><?= htmlspecialchars($row['title']) ?></div>
                    <div class="content-body"><?= nl2br(htmlspecialchars($row['body'])) ?></div>
                    <div class="content-date">Posted on: <?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No updates available at the moment. Please check back later.</p>
        <?php endif; ?>

    </div>

</body>

</html>
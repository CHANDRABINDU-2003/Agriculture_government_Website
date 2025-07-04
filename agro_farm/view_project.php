<?php
session_start();

// Check if user is logged in and role is researcher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'researcher') {
    header("Location: login.php");
    exit();
}

require_once 'db.php'; // This should create $conn as MySQLi connection

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid project ID.";
    exit();
}

$project_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Prepare statement to fetch project only if belongs to logged in researcher
$stmt = $conn->prepare("SELECT * FROM research_projects WHERE id = ? AND researcher_id = ?");
$stmt->bind_param("ii", $project_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Project not found or you do not have permission to view it.";
    exit();
}

$project = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>View Project - <?php echo htmlspecialchars($project['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef3f9;
            padding: 40px;
            max-width: 800px;
            margin: auto;
        }

        h1 {
            color: #0b3d91;
            margin-bottom: 10px;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #333;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            background-color: #0b3d91;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }

        .back-link:hover {
            background-color: #062e6a;
        }
    </style>
</head>

<body>

    <h1><?php echo htmlspecialchars($project['title']); ?></h1>
    <p><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>

    <a class="back-link" href="research_projects.php">← Back to Projects</a>

</body>

</html>
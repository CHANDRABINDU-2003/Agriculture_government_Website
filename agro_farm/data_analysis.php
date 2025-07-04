<?php
session_start();

// Check if user is logged in and role is researcher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'researcher') {
    header("Location: login.php");
    exit();
}

require_once 'db.php'; // your mysqli connection in $conn

$user_id = $_SESSION['user_id'];

// Fetch all projects of this researcher
$stmt = $conn->prepare("SELECT * FROM research_projects WHERE researcher_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$projects = [];
$totalCompletion = 0;

while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
    $totalCompletion += (int)$row['completion_percentage'];
}

$projectCount = count($projects);
$averageCompletion = $projectCount > 0 ? round($totalCompletion / $projectCount, 2) : 0;

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Data Analysis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef3f9;
            padding: 40px;
            max-width: 900px;
            margin: auto;
        }

        h1 {
            color: #0b3d91;
            margin-bottom: 20px;
        }

        .stats {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-around;
        }

        .stat-item {
            text-align: center;
            flex: 1;
            margin: 0 10px;
        }

        .stat-item h2 {
            color: #0b3d91;
            font-size: 2.5rem;
            margin-bottom: 5px;
        }

        .stat-item p {
            font-weight: bold;
            color: #555;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 14px 16px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #0b3d91;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f5f8fc;
        }

        .dashboard-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            text-decoration: none;
            background-color: #084ab4;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        .dashboard-link:hover {
            background-color: #062e6a;
        }

        .progress-bar {
            background-color: #ddd;
            border-radius: 12px;
            overflow: hidden;
            height: 20px;
            width: 100%;
        }

        .progress-fill {
            background-color: #0b3d91;
            height: 100%;
            color: white;
            text-align: center;
            white-space: nowrap;
            font-size: 0.9rem;
            line-height: 20px;
            border-radius: 12px 0 0 12px;
        }
    </style>
</head>

<body>

    <h1>Data Analysis of Your Research Projects</h1>

    <div class="stats">
        <div class="stat-item">
            <h2><?php echo $projectCount; ?></h2>
            <p>Total Projects</p>
        </div>
        <div class="stat-item">
            <h2><?php echo $averageCompletion; ?>%</h2>
            <p>Average Completion</p>
        </div>
    </div>

    <?php if ($projectCount > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Project Title</th>
                    <th>Completion</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['title']); ?></td>
                        <td>
                            <div class="progress-bar" title="<?php echo (int)$p['completion_percentage']; ?>%">
                                <div class="progress-fill" style="width: <?php echo (int)$p['completion_percentage']; ?>%;">
                                    <?php echo (int)$p['completion_percentage']; ?>%
                                </div>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars(substr($p['description'], 0, 70)) . '...'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have not created any projects yet.</p>
    <?php endif; ?>

    <a class="dashboard-link" href="research_projects.php">← Back to Research Projects</a>

</body>

</html>
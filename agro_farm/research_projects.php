<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'researcher') {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

// Handle project creation
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $completion_percentage = intval($_POST['completion_percentage']); 
    $researcher_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO research_projects (title, description, researcher_id, completion_percentage) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $title, $description, $researcher_id, $completion_percentage);
    $stmt->execute();
    $stmt->close();

    header("Location: research_projects.php");
    exit();
}

// Handle project deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM research_projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: research_projects.php");
    exit();
}

// Fetch projects
$stmt = $conn->prepare("SELECT * FROM research_projects WHERE researcher_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$projects = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Research Projects</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef3f9;
            padding: 40px;
        }

        h1 {
            color: #0b3d91;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            margin-bottom: 30px;
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

        .btn {
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            transition: 0.2s ease;
        }

        .view-btn {
            background-color: #0b3d91;
        }

        .edit-btn {
            background-color: #f0ad4e;
        }

        .delete-btn {
            background-color: #d9534f;
        }

        .btn:hover {
            opacity: 0.85;
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

        .create-btn {
            display: block;
            margin: 30px auto;
            background-color: #0b3d91;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        #createForm {
            display: none;
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }

        #createForm h2 {
            margin-bottom: 16px;
            color: #0b3d91;
        }

        #createForm input[type="text"],
        #createForm textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }

        #createForm input[type="submit"] {
            background-color: #0b3d91;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        #createForm input[type="submit"]:hover {
            background-color: #062e6a;
        }

        .completion_percentage-cell {
            width: 120px;
            text-align: center;
            font-weight: bold;
            color: #0b3d91;
        }

        input[type="text"],
        textarea,
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
        }
    </style>
</head>

<body>

    <h1>My Research Projects</h1>

    <?php if (count($projects) > 0): ?>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>completion_percentage (%)</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?php echo htmlspecialchars($project['title']); ?></td>
                    <td>
                        <?php echo substr(htmlspecialchars($project['description']), 0, 50); ?>...
                        <a class="btn view-btn" href="view_project.php?id=<?php echo $project['id']; ?>">Read More</a>
                    </td>
                    <td class="completion_percentage-cell"><?php echo htmlspecialchars($project['completion_percentage']); ?>%</td>
                    <td>
                        <a class="btn edit-btn" href="edit_project.php?id=<?php echo $project['id']; ?>">Edit</a>
                        <a class="btn delete-btn" href="?delete=<?php echo $project['id']; ?>" onclick="return confirm('Are you sure you want to delete this project?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>You haven't created any projects yet.</p>
    <?php endif; ?>

    <!-- Create Project Button -->
    <button class="create-btn" onclick="document.getElementById('createForm').style.display='block'; this.style.display='none';">
        + Create New Project
    </button>

    <!-- Hidden Create Project Form -->
    <form method="POST" id="createForm">
        <h2>Create New Project</h2>
        <input type="text" name="title" placeholder="Project Title" required>
        <textarea name="description" placeholder="Project Description" rows="5" required></textarea>
        <label for="completion_percentage">completion_percentage (%)</label>
        <input type="number" id="completion_percentage" name="completion_percentage" min="0" max="100" value="0" required>
        <input type="submit" name="create" value="Create Project">
    </form>

    <a class="dashboard-link" href="researcher_dashboard.php">← Go to Dashboard</a>

</body>

</html>
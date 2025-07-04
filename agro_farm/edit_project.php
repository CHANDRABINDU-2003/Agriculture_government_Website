<?php
session_start();

// Check if user is logged in and role is researcher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'researcher') {
    header("Location: login.php");
    exit();
}

require_once 'db.php'; // Your MySQLi connection in $conn

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid project ID.";
    exit();
}

$project_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Handle form submission to update project
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $completion = $_POST['completion'] ?? 0;

    // Validate completion percentage between 0 and 100
    $completion = (int)$completion;
    if ($completion < 0) $completion = 0;
    if ($completion > 100) $completion = 100;

    // Update the project only if it belongs to the logged-in researcher
    $stmt = $conn->prepare("UPDATE research_projects SET title = ?, description = ?, completion_percentage = ? WHERE id = ? AND researcher_id = ?");
    $stmt->bind_param("ssiii", $title, $description, $completion, $project_id, $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: research_projects.php");
        exit();
    } else {
        $error = "Error updating project: " . $conn->error;
    }
}

// Fetch project data for the form
$stmt = $conn->prepare("SELECT * FROM research_projects WHERE id = ? AND researcher_id = ?");
$stmt->bind_param("ii", $project_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Project not found or you do not have permission to edit it.";
    exit();
}

$project = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Edit Project - <?php echo htmlspecialchars($project['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef3f9;
            padding: 40px;
            max-width: 600px;
            margin: auto;
        }

        h1 {
            color: #0b3d91;
            margin-bottom: 20px;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
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

        input[type="submit"] {
            background-color: #0b3d91;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #062e6a;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #0b3d91;
            font-weight: bold;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
        
    </style>
</head>

<body>

    <h1>Edit Project</h1>

    <?php if (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="title">Project Title</label>
        <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($project['title']); ?>">

        <label for="description">Project Description</label>
        <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($project['description']); ?></textarea>

        <label for="completion">Completion Percentage (%)</label>
        <input type="number" id="completion" name="completion" min="0" max="100" value="<?php echo (int)$project['completion_percentage']; ?>" required>

        <input type="submit" name="update" value="Update Project">
    </form>

    <a class="back-link" href="research_projects.php">← Back to Projects</a>

</body>

</html>
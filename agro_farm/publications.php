<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'researcher') {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

// Handle publication creation
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create'])) {
    $title = $_POST['title'];
    $journal = $_POST['journal'];
    $publication_date = $_POST['publication_date'];
    $researcher_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO publications (title, journal, publication_date, researcher_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $journal, $publication_date, $researcher_id);
    $stmt->execute();
    $stmt->close();

    header("Location: publications.php");
    exit();
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM publications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: publications.php");
    exit();
}

// Fetch publications
$stmt = $conn->prepare("SELECT * FROM publications WHERE researcher_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$publications = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>My Publications</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f0f4f8;
            padding: 40px;
        }

        h1 {
            color: #0b3d91;
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
            padding: 14px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #0b3d91;
            color: white;
        }

        .btn {
            padding: 6px 12px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            margin-right: 5px;
        }

        .delete-btn {
            background-color: #d9534f;
        }

        .create-btn {
            background-color: #0b3d91;
            color: white;
            padding: 10px 16px;
            margin: 20px 0;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        #createForm {
            max-width: 600px;
            margin-top: 20px;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 14px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #0b3d91;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #062e6a;
        }

        .dashboard-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            background-color: #084ab4;
            color: white;
            padding: 12px;
            text-decoration: none;
            border-radius: 8px;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        .dashboard-link:hover {
            background-color: #062e6a;
        }
    </style>
</head>

<body>

    <h1>My Publications</h1>

    <?php if (count($publications) > 0): ?>
        <table>
            <tr>
                <th>Title</th>
                <th>Journal</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($publications as $pub): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pub['title']); ?></td>
                    <td><?php echo htmlspecialchars($pub['journal']); ?></td>
                    <td><?php echo htmlspecialchars($pub['publication_date']); ?></td>
                    <td>
                        <a class="btn delete-btn" href="?delete=<?php echo $pub['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No publications found.</p>
    <?php endif; ?>

    <!-- Create Publication Form -->
    <button class="create-btn" onclick="document.getElementById('createForm').style.display='block'; this.style.display='none';">
        + Add Publication
    </button>

    <form method="POST" id="createForm" style="display:none;">
        <h2>Add New Publication</h2>
        <input type="text" name="title" placeholder="Publication Title" required>
        <input type="text" name="journal" placeholder="Journal Name" required>
        <input type="date" name="publication_date" required>
        <input type="submit" name="create" value="Add Publication">
    </form>

    <a class="dashboard-link" href="researcher_dashboard.php">← Go to Dashboard</a>

</body>

</html>
<?php
session_start();
require_once 'db.php';

// Check admin login (adjust if needed)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Handle new content creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_content'])) {
    $title = $_POST['title'] ?? '';
    $body = $_POST['body'] ?? '';
    if ($title && $body) {
        $stmt = $conn->prepare("INSERT INTO content (title, body, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $title, $body);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_content.php");
        exit();
    }
}

// Handle content update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_content'])) {
    $id = $_POST['content_id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $body = $_POST['body'] ?? '';
    if ($id && $title && $body) {
        $stmt = $conn->prepare("UPDATE content SET title = ?, body = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $body, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_content.php");
        exit();
    }
}

// Handle content deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM content WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_content.php");
    exit();
}

// Fetch content for editing (if edit_id is set)
$edit_content = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM content WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_content = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all contents for listing
$result = $conn->query("SELECT * FROM content ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Content Management</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }

        form {
            margin-bottom: 30px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
            resize: vertical;
        }

        textarea {
            height: 120px;
        }

        input[type="submit"] {
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #1c5980;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a.action-link {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
            margin-right: 10px;
        }

        a.action-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Content Management</h2>

        <form method="POST">
            <input type="hidden" name="content_id" value="<?= htmlspecialchars($edit_content['id'] ?? '') ?>" />

            <label for="title">Title</label>
            <input type="text" id="title" name="title" required
                value="<?= htmlspecialchars($edit_content['title'] ?? '') ?>" />

            <label for="body">Content Body</label>
            <textarea id="body" name="body" required><?= htmlspecialchars($edit_content['body'] ?? '') ?></textarea>

            <?php if ($edit_content): ?>
                <input type="submit" name="update_content" value="Update Content" />
                <a href="manage_content.php" style="margin-left: 10px; color: #2980b9; font-weight: bold;">Cancel Edit</a>
            <?php else: ?>
                <input type="submit" name="create_content" value="Add Content" />
            <?php endif; ?>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= nl2br(htmlspecialchars(substr($row['body'], 0, 100))) ?>...</td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <a class="action-link" href="manage_content.php?edit=<?= $row['id'] ?>">Edit</a>
                            <a class="action-link" href="manage_content.php?delete=<?= $row['id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this content?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($result->num_rows === 0): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">No content found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div style="text-align: center; margin-top: 20px;">
            <a href="admin_dashboard.php"
                style="
           display: inline-block;
           background-color: #27ae60;
           color: white;
           padding: 12px 25px;
           text-decoration: none;
           font-weight: bold;
           border-radius: 4px;
       ">Go to Dashboard</a>
        </div>

</body>

</html>
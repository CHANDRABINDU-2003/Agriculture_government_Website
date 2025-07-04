<?php
session_start();
require_once 'db.php'; // Make sure this file connects to your database

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_user.php");
    exit();
}

// Handle role update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_role'])) {
    $id = $_POST['user_id'];
    $role = $_POST['role'];
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $role, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_user.php");
    exit();
}

// Handle search and sort
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'asc';
$sort = strtolower($sort) === 'desc' ? 'DESC' : 'ASC';
$search_param = "%" . $search . "%";

$stmt = $conn->prepare("SELECT id, full_name, email, role, created_at FROM users WHERE full_name LIKE ? ORDER BY created_at $sort");
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }

        h2 {
            text-align: center;
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            margin-bottom: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form.search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: space-between;
        }

        input[type="text"],
        select {
            padding: 10px;
            font-size: 16px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 10px 20px;
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
            margin-top: 15px;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        select[name="role"] {
            width: 130px;
        }

        .action-link {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
                align-items: flex-start;
            }

            input[type="text"],
            select {
                width: 100%;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            th,
            td {
                text-align: right;
                position: relative;
                padding-left: 50%;
            }

            th::before,
            td::before {
                position: absolute;
                left: 10px;
                white-space: nowrap;
            }

            td:nth-of-type(1)::before {
                content: "ID";
            }

            td:nth-of-type(2)::before {
                content: "Full Name";
            }

            td:nth-of-type(3)::before {
                content: "Email";
            }

            td:nth-of-type(4)::before {
                content: "Role";
            }

            td:nth-of-type(5)::before {
                content: "Created At";
            }

            td:nth-of-type(6)::before {
                content: "Actions";
            }
        }
    </style>
</head>

<body>

    <h2>Manage User</h2>

    <div class="container">
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by full name" value="<?= htmlspecialchars($search) ?>">
            <select name="sort">
                <option value="asc" <?= $sort === 'ASC' ? 'selected' : '' ?>>Oldest First</option>
                <option value="desc" <?= $sort === 'DESC' ? 'selected' : '' ?>>Newest First</option>
            </select>
            <input type="submit" value="Search">
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                <select name="role">
                                    <option value="admin" <?= $row['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="farmer" <?= $row['role'] === 'farmer' ? 'selected' : '' ?>>Farmer</option>
                                    <option value="researcher" <?= $row['role'] === 'researcher' ? 'selected' : '' ?>>Researcher</option>
                                    <option value="seller" <?= $row['role'] === 'seller' ? 'selected' : '' ?>>Seller</option> <!-- ✅ NEW ROLE ADDED -->
                                </select>
                                <input type="submit" name="update_role" value="Update">
                            </form>
                        </td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <a class="action-link" href="manage_user.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
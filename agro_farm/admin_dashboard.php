<?php
session_start();

// Check if user is logged in and role is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
    <style>
        /* Reset and base */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('ag3.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: rgba(47, 62, 70, 0.9);
            color: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            font-weight: 700;
            font-size: 1.8rem;
            letter-spacing: 1px;
        }

        header div {
            display: flex;
            gap: 12px;
        }

        .header-btn {
            background-color: #4a6f6f;
            border: none;
            padding: 10px 25px;
            color: white;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .header-btn:hover {
            background-color: #3a5454;
        }

        header .logout-btn {
            background-color: #52796f;
            border: none;
            padding: 10px 25px;
            color: white;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        header .logout-btn:hover {
            background-color: #354f52;
        }

        main {
            flex-grow: 1;
            padding: 30px 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .welcome {
            font-size: 1.5rem;
            margin-bottom: 28px;
            color: #1b4332;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            border-radius: 10px;
            display: inline-block;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 14px;
            box-shadow: 0 5px 22px rgba(63, 94, 78, 0.12);
            padding: 28px 30px;
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 12px 38px rgba(63, 94, 78, 0.2);
        }

        .card h2 {
            margin-bottom: 20px;
            color: #2f3e46;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .card p {
            color: #555;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .card button {
            margin-top: 22px;
            background-color: #52796f;
            border: none;
            padding: 14px 28px;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1.05rem;
            transition: background-color 0.3s ease;
        }

        .card button:hover {
            background-color: #354f52;
        }

        footer {
            background-color: rgba(47, 62, 70, 0.9);
            color: white;
            text-align: center;
            padding: 18px 10px;
            font-size: 0.95rem;
            margin-top: auto;
        }
    </style>
</head>

<body>

    <header>
        <h1>Admin Dashboard</h1>
        <div>
            <a href="index.php" class="header-btn">Home</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main>
        <p class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>! 👑</p>

        <div class="grid-container">
            <div class="card">
                <h2>User Management</h2>
                <p>View, add, edit, or remove users such as farmers and researchers on the platform.</p>
                <button onclick="location.href='manage_user.php'">Manage Users</button>
            </div>
            <div class="card">
                <h2>Content Management</h2>
                <p>Publish news, articles, or updates relevant to the agronomy community.</p>
                <button onclick="location.href='manage_content.php'">Manage Content</button>
            </div>
            <div class="card">
                <h2>System Settings</h2>
                <p>Configure website settings, roles, and permissions.</p>
                <button onclick="location.href='system_settings.php'">Configure System</button>
            </div>
            <div class="card">
                <h2>Complaints & Feedback</h2>
                <p>View and manage complaints and feedback submitted by sellers and users.</p>
                <button onclick="location.href='view_complaints.php'">View Complaints</button>
            </div>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Agronomy Farm Portal | Admin Panel
    </footer>

</body>

</html>
<?php
session_start();

// Check if user is logged in and role is researcher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'researcher') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Researcher Dashboard</title>
    <style>
        /* Reset and base styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('ag2.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #0b3d91;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: rgba(11, 61, 145, 0.9);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-weight: 700;
            font-size: 1.8rem;
        }

        header .logout-btn {
            background-color: #084ab4;
            border: none;
            padding: 10px 22px;
            color: white;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        header .logout-btn:hover {
            background-color: #062e6a;
        }

        main {
            flex-grow: 1;
            padding: 30px 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .welcome {
            font-size: 1.4rem;
            margin-bottom: 25px;
            background-color: rgba(255, 255, 255, 0.8);
            display: inline-block;
            padding: 10px 16px;
            border-radius: 8px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 28px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(11, 61, 145, 0.1);
            padding: 25px 30px;
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 30px rgba(11, 61, 145, 0.18);
        }

        .card h2 {
            margin-bottom: 18px;
            color: #0b3d91;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .card p {
            color: #555;
            font-size: 1.05rem;
            line-height: 1.5;
        }

        .card button {
            margin-top: 20px;
            background-color: #0b3d91;
            border: none;
            padding: 12px 24px;
            color: white;
            border-radius: 7px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .card button:hover {
            background-color: #062e6a;
        }

        footer {
            background-color: rgba(11, 61, 145, 0.9);
            color: white;
            text-align: center;
            padding: 18px 10px;
            font-size: 0.9rem;
            margin-top: auto;
        }
    </style>
</head>

<body>
    <header>
        <h1>Researcher Dashboard</h1>
        <div>
            <a href="index.php" class="logout-btn" style="margin-right: 10px; background-color: #0b3d91;">Home</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main>
        <p class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>! 🔬</p>

        <div class="grid-container">
            <div class="card">
                <h2>Research Projects</h2>
                <p>Manage your ongoing and upcoming research projects related to crop science and agronomy.</p>
                <button onclick="window.location.href='research_projects.php'">Manage Projects</button>
            </div>
            <div class="card">
                <h2>Data Analysis</h2>
                <p>Upload datasets and perform statistical analysis to derive meaningful insights.</p>
                <button onclick="window.location.href='data_analysis.php'">Analyze Data</button>
            </div>
            <div class="card">
                <h2>Publications</h2>
                <p>View and submit scientific papers, articles, and reports to share your findings.</p>
                <button onclick="window.location.href='publications.php'">Manage Publications</button>
            </div>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Agronomy Farm Portal | All rights reserved.
    </footer>

</body>

</html>
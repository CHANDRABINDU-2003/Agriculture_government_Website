<?php
session_start();

// Check if user is logged in and role is seller
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

// Store full name for display
$full_name = isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : 'Seller';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Seller Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('ag4.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #2e7d32;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: rgba(56, 142, 60, 0.9);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-weight: 700;
            font-size: 1.8rem;
        }

        .header-btn,
        .logout-btn {
            border: none;
            padding: 10px 20px;
            color: white;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .header-btn {
            background-color: #6a9a4f;
            margin-right: 10px;
        }

        .header-btn:hover {
            background-color: #4e7738;
        }

        .logout-btn {
            background-color: #2e7d32;
        }

        .logout-btn:hover {
            background-color: #1b4d21;
        }

        main {
            flex-grow: 1;
            padding: 30px 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .welcome {
            font-size: 1.3rem;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            display: inline-block;
            padding: 10px 18px;
            border-radius: 10px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .card h2 {
            margin-bottom: 15px;
            color: #2e7d32;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .card p {
            color: #555;
            font-size: 1rem;
            line-height: 1.4;
        }

        .card button {
            margin-top: 15px;
            background-color: #388e3c;
            border: none;
            padding: 10px 18px;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .card button:hover {
            background-color: #2e7d32;
        }

        footer {
            background-color: rgba(56, 142, 60, 0.9);
            color: white;
            text-align: center;
            padding: 15px 10px;
            font-size: 0.9rem;
            margin-top: auto;
        }
    </style>
</head>

<body>

    <header>
        <h1>Seller Dashboard</h1>
        <div>
            <a href="index.php" class="header-btn">Home</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main>
        <p class="welcome">Welcome, <?php echo $full_name; ?>! 🧑‍🌾</p>

        <div class="grid-container">
            <div class="card">
                <h2>Required Crops</h2>
                <p>Manage and track crops you need to buy from farmers.</p>
                <button onclick="location.href='required_crops.php'">Manage Requirements</button>
            </div>
            <div class="card">
                <h2>Nearby Farmers</h2>
                <p>Find farmers near your location and contact them directly.</p>
                <button onclick="location.href='nearby_farmers.php'">View Farmers</button>
            </div>
            <div class="card">
                <h2>Complain Box</h2>
                <p>Submit issues related to crop quality, delivery, or any other concerns.</p>
                <button onclick="location.href='complain_box.php'">Submit Complaint</button>
            </div>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Agri Trade Platform | All rights reserved.
    </footer>

</body>

</html>
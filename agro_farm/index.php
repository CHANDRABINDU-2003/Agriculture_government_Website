<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user info
$user_name = $_SESSION['full_name'] ?? 'Guest';
$role = $_SESSION['role'] ?? 'unknown';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agro Farm - Home</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome, <?= htmlspecialchars($user_name) ?>!</h1>

            <?php
            // Conditional dashboard redirection
            if ($role == 'admin') {
                echo '<a href="admin_dashboard.php" class="btn">Go to Admin Dashboard</a>';
            } elseif ($role == 'farmer') {
                echo '<a href="farmer_dashboard.php" class="btn">Go to Farmer Dashboard</a>';
            } elseif ($role == 'researcher') {
                echo '<a href="researcher_dashboard.php" class="btn">Go to Researcher Dashboard</a>';
            } else {
                echo '<p style="color: red;">Invalid role. Please contact admin.</p>';
            }
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; <?= date('Y') ?> Agro Farm. All rights reserved.</p>
    </footer>

</body>

</html>
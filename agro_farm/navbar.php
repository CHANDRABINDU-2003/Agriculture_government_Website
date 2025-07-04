<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? 'guest';
?>

<header class="navbar">
    <div class="logo">🌾 Agro Farm</div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>

            <?php if ($role == 'admin'): ?>
                <li><a href="manage_user.php">Manage Users</a></li>
                <li><a href="manage_content.php">Manage Content</a></li>
                <li><a href="system_settings.php">System Settings</a></li>
            <?php elseif ($role == 'farmer'): ?>
                <li><a href="my_crops.php">My Crops</a></li>
                <li><a href="weather.php">Weather Forecast</a></li>
                <li><a href="helpline.php">Helpline</a></li>
                <li><a href="review.php">Review</a></li>
            <?php elseif ($role == 'researcher'): ?>
                <li><a href="research_projects.php">Research Projects</a></li>
                <li><a href="publications.php">Publications</a></li>
            <?php endif; ?>

            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>
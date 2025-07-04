<?php
session_start();
require_once 'db.php'; // your DB connection file

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = $_POST['site_name'] ?? '';
    $admin_email = $_POST['admin_email'] ?? '';
    $maintenance_mode = isset($_POST['maintenance_mode']) ? '1' : '0';

    // Update settings in DB
    $stmt = $conn->prepare("UPDATE system_settings SET setting_value = ? WHERE setting_key = ?");

    $key = 'site_name';
    $stmt->bind_param("ss", $site_name, $key);
    $stmt->execute();

    $key = 'admin_email';
    $stmt->bind_param("ss", $admin_email, $key);
    $stmt->execute();

    $key = 'maintenance_mode';
    $stmt->bind_param("ss", $maintenance_mode, $key);
    $stmt->execute();

    $stmt->close();

    $message = "Settings updated successfully!";
}

// Fetch current settings
$sql = "SELECT setting_key, setting_value FROM system_settings";
$result = $conn->query($sql);

$settings = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>System Settings</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            background: white;
            margin: auto;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }

        .checkbox-label input {
            margin-right: 8px;
        }

        button {
            margin-top: 25px;
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 12px 20px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #1c5980;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            color: green;
        }

        a.back-link {
            display: inline-block;
            margin-top: 20px;
            color: #2980b9;
            text-decoration: none;
        }

        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>System Settings</h2>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="site_name">Site Name</label>
            <input type="text" id="site_name" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" required />

            <label for="admin_email">Admin Email</label>
            <input type="email" id="admin_email" name="admin_email" value="<?= htmlspecialchars($settings['admin_email'] ?? '') ?>" required />

            <label class="checkbox-label">
                <input type="checkbox" name="maintenance_mode" <?= isset($settings['maintenance_mode']) && $settings['maintenance_mode'] === '1' ? 'checked' : '' ?> />
                Enable Maintenance Mode
            </label>

            <button type="submit">Save Settings</button>
        </form>

        <a href="admin_dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>

</html>
<?php
session_start();
require_once 'db.php';

// Check if user is logged in and is a seller
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get seller's latitude and longitude from the database
$stmt = $conn->prepare("SELECT latitude, longitude FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$seller_location = $result->fetch_assoc();
$stmt->close();

if (!$seller_location || !$seller_location['latitude'] || !$seller_location['longitude']) {
    echo "Your location is not set. Please update your profile.";
    exit();
}

$seller_lat = $seller_location['latitude'];
$seller_lng = $seller_location['longitude'];
$radius = 50; // radius in kilometers

// Haversine Formula in SQL to find nearby farmers
$sql = "
    SELECT id, full_name, email, latitude, longitude,
    (6371 * acos(
        cos(radians(?)) * cos(radians(latitude)) *
        cos(radians(longitude) - radians(?)) +
        sin(radians(?)) * sin(radians(latitude))
    )) AS distance
    FROM users
    WHERE role = 'farmer'
    HAVING distance <= ?
    ORDER BY distance ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("dddd", $seller_lat, $seller_lng, $seller_lat, $radius);
$stmt->execute();
$result = $stmt->get_result();
$farmers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Nearby Farmers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f9f4;
            padding: 20px;
            max-width: 900px;
            margin: auto;
        }

        h1 {
            color: #2e7d32;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #388e3c;
            color: white;
        }

        tr:hover {
            background-color: #f1f9f1;
        }

        .no-farmers {
            text-align: center;
            color: #777;
            padding: 40px 0;
        }

        a.back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            text-decoration: none;
            color: #388e3c;
            font-weight: bold;
        }

        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <h1>Nearby Farmers (within <?php echo $radius; ?> km)</h1>

    <?php if (count($farmers) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Farmer Name</th>
                    <th>Email</th>
                    <th>Distance (km)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($farmers as $farmer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($farmer['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($farmer['email']); ?></td>
                        <td><?php echo round($farmer['distance'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-farmers">No farmers found within <?php echo $radius; ?> km of your location.</p>
    <?php endif; ?>

    <a href="seller_dashboard.php" class="back-link">← Back to Dashboard</a>

</body>

</html>
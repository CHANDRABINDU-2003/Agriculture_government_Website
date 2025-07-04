<?php
session_start();

// Check if user is logged in and role is farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'farmer') {
    header("Location: login.php");
    exit();
}

// Simulate weather data
$weather = [
    'temperature' => '29°C',
    'humidity' => '78%',
    'wind_speed' => '12 km/h',
    'condition' => 'Chance of Rain 🌧️' // Change this value to test
];

// Check for rain
$is_rain_expected = stripos($weather['condition'], 'rain') !== false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Weather Forecast</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e8f5e9;
            padding: 40px;
        }

        .weather-box {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .weather-box h2 {
            color: #2e7d32;
            margin-bottom: 20px;
        }

        .info {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .alert {
            background-color: #ffebee;
            color: #c62828;
            padding: 12px;
            border: 1px solid #f44336;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 20px;
        }

        .back-link {
            margin-top: 25px;
            display: inline-block;
            color: #2e7d32;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="weather-box">
        <h2>Today's Weather Forecast</h2>
        <div class="info">🌡️ Temperature: <?php echo $weather['temperature']; ?></div>
        <div class="info">💧 Humidity: <?php echo $weather['humidity']; ?></div>
        <div class="info">🌬️ Wind Speed: <?php echo $weather['wind_speed']; ?></div>
        <div class="info">🌤️ Condition: <?php echo $weather['condition']; ?></div>

        <?php if ($is_rain_expected): ?>
            <div class="alert">⚠️ Alert: There is a chance of rain today. Please take precautions!</div>
        <?php endif; ?>

        <a class="back-link" href="farmer_dashboard.php">← Back to Dashboard</a>
    </div>

</body>

</html> 
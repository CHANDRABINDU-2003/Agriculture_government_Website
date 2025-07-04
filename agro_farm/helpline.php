<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Farmer Helpline Links</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f6f9;
        }

        h2 {
            background-color: #2e7d32;
            color: white;
            padding: 20px;
            text-align: center;
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .link-box {
            background-color: #e8f5e9;
            border: 1px solid #c8e6c9;
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 6px;
            transition: 0.3s ease;
        }

        .link-box:hover {
            background-color: #d0f0d0;
        }

        .link-box a {
            color: #1b5e20;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }

        .link-box p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #555;
        }

        .back-button {
            display: block;
            margin-top: 30px;
            text-align: center;
        }

        .back-button a {
            background-color: #388e3c;
            color: white;
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .back-button a:hover {
            background-color: #256029;
        }
    </style>
</head>

<body>

    <h2>Farmer Helpline & Support Links</h2>

    <div class="container">

        <div class="link-box">
            <a href="https://www.krishi.gov.bd/" target="_blank">Department of Agricultural Extension (DAE)</a>
            <p>Official site of DAE Bangladesh for farmer support, news, and services.</p>
        </div>

        <div class="link-box">
            <a href="https://www.bmet.gov.bd" target="_blank">Bangladesh Meteorological Department</a>
            <p>Get updated weather forecasts essential for farming activities.</p>
        </div>

        <div class="link-box">
            <a href="https://www.amis.minagric.gov.bd" target="_blank">Agricultural Market Information System (AMIS)</a>
            <p>Provides up-to-date prices of crops and agricultural market data.</p>
        </div>

        <div class="link-box">
            <a href="https://www.fao.org/bangladesh/" target="_blank">FAO Bangladesh</a>
            <p>Food and Agriculture Organization support and reports for Bangladesh.</p>
        </div>

        <div class="link-box">
            <a href="tel:16123">Farmer Helpline 16123</a>
            <p>Dial this helpline number to get government support services directly.</p>
        </div>

        <div class="back-button">
            <a href="farmer_dashboard.php">← Go to Dashboard</a>
        </div>

    </div>

</body>

</html>
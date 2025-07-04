<?php
session_start();

// Redirect if not logged in as farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'farmer') {
    header("Location: login.php");
    exit();
}

// Example market prices – this could come from a database later
$prices = [
    ["crop" => "Rice", "price" => 42, "unit" => "kg"],
    ["crop" => "Wheat", "price" => 38, "unit" => "kg"],
    ["crop" => "Potato", "price" => 25, "unit" => "kg"],
    ["crop" => "Tomato", "price" => 35, "unit" => "kg"],
    ["crop" => "Onion", "price" => 45, "unit" => "kg"],
    ["crop" => "Garlic", "price" => 95, "unit" => "kg"],
    ["crop" => "Chili", "price" => 110, "unit" => "kg"],
];

// Handle search query
$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    if ($search !== '') {
        // Filter prices by crop name (case-insensitive)
        $prices = array_filter($prices, function ($item) use ($search) {
            return stripos($item['crop'], $search) !== false;
        });
    }
}

// Handle sorting by price (asc or desc)
$sort = '';
if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    if ($sort === 'asc') {
        usort($prices, function ($a, $b) {
            return $a['price'] <=> $b['price'];
        });
    } elseif ($sort === 'desc') {
        usort($prices, function ($a, $b) {
            return $b['price'] <=> $a['price'];
        });
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Market Prices</title>
    <style>
        body {
            background-color: #f4f9f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px;
            color: #2e7d32;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2e7d32;
        }

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="text"] {
            width: 45%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #388e3c;
            border-radius: 6px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #2e7d32;
        }

        select {
            padding: 10px 12px;
            font-size: 16px;
            border: 2px solid #388e3c;
            border-radius: 6px;
            margin-left: 10px;
            cursor: pointer;
            outline: none;
            transition: border-color 0.3s ease;
        }

        select:hover,
        select:focus {
            border-color: #2e7d32;
        }

        input[type="submit"] {
            padding: 10px 18px;
            font-size: 16px;
            background-color: #388e3c;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            margin-left: 10px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2e7d32;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #388e3c;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            text-decoration: none;
            color: #388e3c;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .no-results {
            text-align: center;
            font-style: italic;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>📈 Current Market Prices</h2>

        <form method="GET" action="">
            <input
                type="text"
                name="search"
                placeholder="Search by crop name..."
                value="<?php echo htmlspecialchars($search); ?>"
                autocomplete="off" />
            <select name="sort">
                <option value="" <?php if ($sort === '') echo 'selected'; ?>>Sort by Price</option>
                <option value="asc" <?php if ($sort === 'asc') echo 'selected'; ?>>Low to High</option>
                <option value="desc" <?php if ($sort === 'desc') echo 'selected'; ?>>High to Low</option>
            </select>
            <input type="submit" value="Apply" />
        </form>

        <?php if (count($prices) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Crop</th>
                        <th>Price (BDT)</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prices as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['crop']); ?></td>
                            <td><?php echo $item['price']; ?></td>
                            <td><?php echo $item['unit']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-results">No crops found matching your search.</p>
        <?php endif; ?>

        <a href="farmer_dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>

</body>

</html>
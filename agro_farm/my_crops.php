<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// DELETE functionality
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM crops WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: my_crops.php");
    exit();
}

// Initialize variables for form
$edit_crop = null;
$crop_name = '';
$expected_harvest = '';
$quantity = '';
$edit_id = null;

// EDIT functionality - load crop data into form
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT crop_name, expected_harvest, quantity FROM crops WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $edit_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_crop = $result->fetch_assoc();
    $stmt->close();

    if ($edit_crop) {
        $crop_name = $edit_crop['crop_name'];
        $expected_harvest = $edit_crop['expected_harvest'];
        $quantity = $edit_crop['quantity'];
    } else {
        // invalid edit id or crop does not belong to user
        header("Location: my_crops.php");
        exit();
    }
}

// FORM submission for ADD or UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $crop_name = trim($_POST['crop_name']);
    $expected_harvest = $_POST['expected_harvest'];
    $quantity = trim($_POST['quantity']);
    $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : null;

    if ($edit_id) {
        // UPDATE existing crop
        $stmt = $conn->prepare("UPDATE crops SET crop_name=?, expected_harvest=?, quantity=? WHERE id=? AND user_id=?");
        $stmt->bind_param("sssii", $crop_name, $expected_harvest, $quantity, $edit_id, $user_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // INSERT new crop
        $stmt = $conn->prepare("INSERT INTO crops (user_id, crop_name, expected_harvest, quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $crop_name, $expected_harvest, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    // After submission clear form and redirect to avoid resubmission
    header("Location: my_crops.php");
    exit();
}

// Fetch all crops for this user
$stmt = $conn->prepare("SELECT id, crop_name, expected_harvest, quantity FROM crops WHERE user_id=? ORDER BY expected_harvest ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$crops = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Crops</title>
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

        form {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #2e7d32;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            margin-top: 20px;
            background-color: #388e3c;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2e7d32;
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
            color: #333;
        }

        th {
            background-color: #388e3c;
            color: white;
            border-radius: 12px 12px 0 0;
        }

        tr:hover {
            background-color: #f1f9f1;
        }

        .actions a {
            margin-right: 12px;
            text-decoration: none;
            color: #388e3c;
            font-weight: bold;
        }

        .actions a.delete {
            color: red;
        }

        .actions a.delete:hover {
            text-decoration: underline;
        }

        .no-crops {
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

    <h1>My Crops</h1>

    <form method="POST" action="my_crops.php">
        <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($edit_id ?? ''); ?>">

        <label for="crop_name">Crop Name:</label>
        <input type="text" id="crop_name" name="crop_name" required value="<?php echo htmlspecialchars($crop_name); ?>">

        <label for="expected_harvest">Expected Harvest Date:</label>
        <input type="date" id="expected_harvest" name="expected_harvest" required value="<?php echo htmlspecialchars($expected_harvest); ?>">

        <label for="quantity">Quantity:</label>
        <input type="number" min="1" id="quantity" name="quantity" required value="<?php echo htmlspecialchars($quantity); ?>">

        <button type="submit"><?php echo $edit_id ? 'Update Crop' : 'Add Crop'; ?></button>
    </form>

    <?php if (count($crops) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Crop Name</th>
                    <th>Expected Harvest Date</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crops as $crop): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($crop['crop_name']); ?></td>
                        <td><?php echo htmlspecialchars($crop['expected_harvest']); ?></td>
                        <td><?php echo htmlspecialchars($crop['quantity']); ?> kg</td>
                        <td class="actions">
                            <a href="my_crops.php?edit_id=<?php echo $crop['id']; ?>">Edit</a>
                            <a href="my_crops.php?delete_id=<?php echo $crop['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this crop?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-crops">You have no crops added yet.</p>
    <?php endif; ?>
    <a href="farmer_dashboard.php" class="back-link">← Back to Dashboard</a>

</body>

</html>
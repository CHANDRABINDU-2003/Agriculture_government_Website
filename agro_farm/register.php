<?php
include 'db.php'; // Connect to database
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $full_name  = trim($_POST['full_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $address    = trim($_POST['address'] ?? '');
    $profession = $_POST['profession'] ?? '';
    $role       = $_POST['role'] ?? '';
    $password   = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $latitude   = $_POST['latitude'] ?? null;
    $longitude  = $_POST['longitude'] ?? null;

    // Validation
    if (!$full_name || !$email || !$password || !$confirm_password || !$role || !$profession) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = "Profile image is required.";
    } else {
        // Handle image upload
        $image_name = '';
        if ($_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $image_name = time() . '_' . basename($_FILES['profile_image']['name']);
            $target_file = $target_dir . $image_name;

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = mime_content_type($_FILES['profile_image']['tmp_name']);

            if (!in_array($file_type, $allowed_types)) {
                $error = "Only JPG, PNG, and GIF files are allowed.";
            } elseif (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Error uploading image.";
        }
    }

    // Insert if no errors
    if (!$error) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $latitude = (float)$latitude;
        $longitude = (float)$longitude;

        $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, address, profession, role, password, profile_image, latitude, longitude, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssssssdd", $full_name, $email, $phone, $address, $profession, $role, $hashed_password, $image_name, $latitude, $longitude);

        if ($stmt->execute()) {
            header("Location: login.php?registered=true");
            exit();
        } else {
            $error = "Registration failed: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register-container {
            background: white;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1.5px solid #ccc;
            border-radius: 6px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #28a745;
            border: none;
            padding: 14px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .error-msg {
            background: #f8d7da;
            padding: 10px;
            border-left: 4px solid #dc3545;
            margin-bottom: 15px;
            color: #721c24;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>User Registration</h2>

        <?php if ($error): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" novalidate>
            <input type="text" name="full_name" placeholder="Full Name *" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
            <input type="email" name="email" placeholder="Email *" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            <input type="text" name="phone" placeholder="Phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            <textarea name="address" placeholder="Address"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>

            <select name="profession" required>
                <option value="" disabled <?= empty($_POST['profession']) ? 'selected' : '' ?>>Select Profession *</option>
                <option value="farmer" <?= ($_POST['profession'] ?? '') === 'farmer' ? 'selected' : '' ?>>Farmer</option>
                <option value="researcher" <?= ($_POST['profession'] ?? '') === 'researcher' ? 'selected' : '' ?>>Researcher</option>
                <option value="student" <?= ($_POST['profession'] ?? '') === 'student' ? 'selected' : '' ?>>Student</option>
                <option value="seller" <?= ($_POST['profession'] ?? '') === 'seller' ? 'selected' : '' ?>>Seller</option>
                <option value="others" <?= ($_POST['profession'] ?? '') === 'others' ? 'selected' : '' ?>>Others</option>
            </select>

            <select name="role" required>
                <option value="" disabled <?= empty($_POST['role']) ? 'selected' : '' ?>>Select Role *</option>
                <option value="farmer" <?= ($_POST['role'] ?? '') === 'farmer' ? 'selected' : '' ?>>Farmer</option>
                <option value="admin" <?= ($_POST['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="researcher" <?= ($_POST['role'] ?? '') === 'researcher' ? 'selected' : '' ?>>Researcher</option>
                <option value="seller" <?= ($_POST['role'] ?? '') === 'seller' ? 'selected' : '' ?>>Seller</option>
            </select>

            <input type="password" name="password" placeholder="Password *" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password *" required>

            <label>Profile Image *</label>
            <input type="file" name="profile_image" accept="image/*" required>

            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <input type="submit" value="Register">
        </form>
    </div>

    <script>
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
        }, function(error) {
            console.warn("Geolocation error: " + error.message);
        });
    </script>
</body>

</html>
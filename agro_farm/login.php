<?php
session_start();
include 'db.php';

$show_success = isset($_GET['registered']) && $_GET['registered'] === 'true';
$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = "Please enter both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                if (empty($user['role'])) {
                    $error = "User role not set. Please contact admin.";
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect based on role
                    switch (strtolower($user['role'])) {
                        case 'admin':
                            header("Location: admin_dashboard.php");
                            exit();
                        case 'farmer':
                            header("Location: farmer_dashboard.php");
                            exit();
                        case 'researcher':
                            header("Location: researcher_dashboard.php");
                            exit();
                        case 'seller':
                            header("Location: seller_dashboard.php");
                            exit();
                        default:
                            $error = "Unknown role: " . htmlspecialchars($user['role']);
                    }
                }
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "No user found with that email.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Agronomy Farm</title>
    <style>
        body {
            background: url('hero-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            width: 360px;
        }

        h2 {
            text-align: center;
            margin-bottom: 24px;
            color: #2c3e50;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 16px;
            border: 1.8px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #4caf50;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #43a047;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-weight: bold;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .register-link {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }

        .register-link a {
            color: #4caf50;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login to Agronomy Farm</h2>

        <?php if ($show_success): ?>
            <div class="alert success">✅ Registration successful! Please login.</div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your email" required />
            <input type="password" name="password" placeholder="Enter your password" required />
            <input type="submit" value="Login" />
        </form>

        <div class="register-link">
            Don’t have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</body>

</html>
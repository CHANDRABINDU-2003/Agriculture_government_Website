<?php
session_start();
require_once 'db.php';

// Check if user is logged in and role is farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'farmer') {
    header("Location: login.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get values from user input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }
    if (empty($subject)) {
        $errors[] = "Subject is required.";
    }
    if (empty($message)) {
        $errors[] = "Message cannot be empty.";
    }

    if (empty($errors)) {
        // Insert feedback into DB
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("issss", $_SESSION['user_id'], $name, $email, $subject, $message);

        if ($stmt->execute()) {
            $stmt->close();
            // Redirect to dashboard after success
            header("Location: farmer_dashboard.php?feedback=success");
            exit();
        } else {
            $errors[] = "Failed to send feedback. Please try again later.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Feedback to Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f9f4;
            color: #2e7d32;
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background: white;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
        }

        h1 {
            margin-bottom: 20px;
            color: #2e7d32;
            font-weight: 700;
            font-size: 1.8rem;
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2e7d32;
        }

        form input[type="text"],
        form input[type="email"],
        form textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 18px;
            border: 1.8px solid #a5d6a7;
            border-radius: 6px;
            font-size: 1rem;
            font-family: inherit;
            resize: vertical;
        }

        form textarea {
            min-height: 120px;
        }

        form button {
            background-color: #388e3c;
            border: none;
            padding: 12px 24px;
            color: white;
            border-radius: 7px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #2e7d32;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1.5px solid #f5c6cb;
        }

        .home-link {
            display: inline-block;
            margin-top: 15px;
            color: #2e7d32;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .home-link:hover {
            color: #145214;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Send Feedback to Admin</h1>

        <?php if (!empty($errors)) : ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error) {
                        echo "<li>" . htmlspecialchars($error) . "</li>";
                    } ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST" novalidate>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required placeholder="Enter your name"
                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" />

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />

            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" required placeholder="Enter subject"
                value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" />

            <label for="message">Message</label>
            <textarea id="message" name="message" required
                placeholder="Write your feedback here..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>

            <button type="submit">Send Feedback</button>
        </form>

        <a href="farmer_dashboard.php" class="home-link">&larr; Back to Dashboard</a>
    </div>

</body>

</html>
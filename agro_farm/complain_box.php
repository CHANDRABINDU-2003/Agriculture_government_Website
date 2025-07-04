<?php
session_start();
require_once 'db.php';

// শুধুমাত্র Seller ইউজারদের জন্য এক্সেস খোলা থাকবে
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ইউজারের ইনপুট নেওয়া
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

    // যদি কোনো এরর না থাকে, তাহলে DB তে ইনসার্ট করো
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO complaints (user_id, name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("issss", $_SESSION['user_id'], $name, $email, $subject, $message);

        if ($stmt->execute()) {
            $success = "Your complaint has been submitted successfully.";
            // ফর্ম ফিল্ডগুলো ক্লিয়ার করতে চাইলে:
            $_POST = [];
        } else {
            $errors[] = "Failed to submit complaint. Please try again later.";
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
    <title>Complain Box - Submit Your Complaint</title>
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

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1.5px solid #c3e6cb;
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
        <h1>Submit Your Complaint</h1>

        <?php if (!empty($errors)) : ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error) {
                        echo "<li>" . htmlspecialchars($error) . "</li>";
                    } ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success) : ?>
            <div class="success">
                <?php echo htmlspecialchars($success); ?>
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
                placeholder="Write your complaint here..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>

            <button type="submit">Submit Complaint</button>
        </form>

        <a href="seller_dashboard.php" class="home-link">&larr; Back to Dashboard</a>
    </div>

</body>

</html>
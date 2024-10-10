<?php
require 'db.php'; // Include database connection
session_start(); // Start the session

// Initialize feedback messages
$feedback = "";

// Handle user registration
if (isset($_POST['register'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback = "<span class='error'>Invalid email format.</span>";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        // Prepare and execute insert statement
        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        try {
            if ($stmt->execute([$email, $passwordHash])) {
                $feedback = "<span class='feedback'>Registration successful! You can now log in.</span>";
            } else {
                $feedback = "<span class='error'>Error: User could not be registered.</span>";
            }
        } catch (PDOException $e) {
            $feedback = "<span class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</span>";
        }
    }
}

// Handle user login
if (isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Prepare and execute select statement
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $feedback = "<span class='feedback'>Login successful! Welcome, " . htmlspecialchars($user['email']) . ".</span>";
    } else {
        $feedback = "<span class='error'>Invalid email or password.</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication System</title>
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    form {
        margin-bottom: 20px;
    }

    input {
        margin: 5px 0;
        padding: 10px;
        width: 100%;
        max-width: 300px;
    }

    button {
        padding: 10px;
        background-color: #007BFF;
        color: white;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    .feedback {
        color: green;
    }

    .error {
        color: red;
    }
    </style>
</head>

<body>
    <h1>User Authentication System</h1>

    <div class="feedback"><?php echo $feedback; ?></div>

    <h2>Register</h2>
    <form method="POST">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit" name="register">Register</button>
    </form>

    <h2>Login</h2>
    <form method="POST">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit" name="login">Login</button>
    </form>

    <h2>Password Recovery</h2>
    <form method="POST" action="recover.php">
        <input type="email" name="email" required placeholder="Enter your email to recover">
        <button type="submit">Send Recovery Email</button>
    </form>
</body>

</html>
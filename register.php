<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    require 'db.php';
    require 'functions.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        if (register($username, $password, $email)) {
            echo "Registration successful!";
        } else {
            echo "Registration failed.";
        }
    }
    ?>
    <form method="POST">
        <input type="text" name="username" required placeholder="Username">
        <input type="password" name="password" required placeholder="Password">
        <input type="email" name="email" required placeholder="Email">
        <button type="submit">Register</button>
    </form>

</body>

</html>
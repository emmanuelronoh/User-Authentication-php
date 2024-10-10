<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    ?>
    <h1>Welcome to the Dashboard!</h1>
    <p>Your role: <?php echo $_SESSION['role']; ?></p>
    <a href="logout.php">Logout</a>

</body>

</html>
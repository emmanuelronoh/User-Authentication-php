<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection</title>
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    .error {
        color: red;
    }

    .success {
        color: green;
    }
    </style>
</head>

<body>
    <?php
    $host = 'localhost';
    $dbname = 'auth_db'; // Replace with your actual database name
    $user = 'php'; // Replace with your PostgreSQL username
    $pass = '12345'; // Replace with your PostgreSQL password

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<p class='success'>Connection successful!</p>";
    } catch (PDOException $e) {
        echo "<p class='error'>Connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>
</body>

</html>
<?php
require 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND token_expiration > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['password'];
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update password and clear the token
            $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiration = NULL WHERE id = ?");
            $stmt->execute([$hashedPassword, $user['id']]);
            echo "Password has been reset!";
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>
<form method="POST">
    <input type="password" name="password" required placeholder="Enter new password">
    <button type="submit">Reset Password</button>
</form>
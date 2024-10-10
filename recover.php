<?php
require 'db.php';
require 'vendor/autoload.php'; // Include Composer's autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Generate a token
    $token = bin2hex(random_bytes(50));
    $expiration = date("Y-m-d H:i:s", strtotime('+1 hour'));

    // Store the token and expiration in the database
    $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, token_expiration = ? WHERE email = ?");
    if ($stmt->execute([$token, $expiration, $email])) {
        // Send reset email
        $resetLink = "http://yourdomain.com/reset.php?token=" . $token; // Update to your actual domain

        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
            $mail->SMTPAuth = true;          // Enable SMTP authentication
            $mail->Username = 'eronoh036@gmail.com'; // Your full Gmail address
            $mail->Password = 'your_app_password'; // Use an App Password here
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587;                // TCP port to connect to

            // Recipients
            $mail->setFrom('eronoh036@gmail.com', 'Your Name'); // Your full Gmail address
            $mail->addAddress($email); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click the following link to reset your password: <a href='$resetLink'>$resetLink</a>";

            $mail->send();
            echo "Check your email for the password reset link.";
        } catch (Exception $e) {
            // Log the error message for debugging
            error_log("Mailer Error: {$mail->ErrorInfo}");
            echo "There was an error sending the email. Please try again.";
        }
    } else {
        echo "No user found with that email.";
    }
}
?>
<form method="POST">
    <input type="email" name="email" required placeholder="Enter your email">
    <button type="submit">Send Recovery Email</button>
</form>
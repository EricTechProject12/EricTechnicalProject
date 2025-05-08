<?php
require 'vendor/autoload.php'; // Always load autoloader first!

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(16));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));
    
        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $update->bind_param("sss", $token, $expires, $email);
        $update->execute();

        $resetLink = "http://yourwebsite.com/reset-password.php?token=$token";
    
        // Now send email using PHPMailer.
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.yourmailserver.com';  // e.g., smtp.gmail.com
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com'; // your real email
            $mail->Password = 'your_email_password';      // your real email password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            $mail->setFrom('no-reply@yourwebsite.com', 'Your Website');
            $mail->addAddress('recipient@example.com'); // Replace recipient@example.com with $email if sending to user's email
            $mail->Subject = 'Password Reset Request';
            $mail->isHTML(false); // Use plain text email
            $mail->Body = "Click this link to reset your password:\n$resetLink\n\nThis link will expire in 1 hour.";
        
            $mail->send();
            echo "<h2>Reset Email Sent</h2><p>A password reset link was sent to <strong>$email</strong>.</p>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "<h2>Email Not Found</h2><p>This email is not registered in our system.</p>";
    }
}
$conn->close();
?>

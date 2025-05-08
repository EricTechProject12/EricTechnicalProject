<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];
    $newPassword = password_hash($_POST["new_password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT email FROM users WHERE reset_token = ? AND reset_expires >= NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Invalid or expired token.");
    }

    $email = $result->fetch_assoc()['email'];

    $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE email = ?");
    $update->bind_param("ss", $newPassword, $email);
    $update->execute();

    header("Location: password-reset-success.php");
    exit();
    
}
$conn->close();
?>

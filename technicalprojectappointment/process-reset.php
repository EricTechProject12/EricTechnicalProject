<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "SELECT email FROM users WHERE reset_token=? AND reset_expires >= NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        $update = "UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE email=?";
        $stmtUpdate = $conn->prepare($update);
        $stmtUpdate->bind_param("ss", $hashedPassword, $email);
        $stmtUpdate->execute();

        echo "<h2>Password Reset Successful!</h2>";
        echo "<p><a href='login.php'>Return to Login</a></p>";
    } else {
        echo "<h2>Invalid or Expired Token</h2>";
        echo "<p>The reset link is invalid or has expired.</p>";
    }
}
$conn->close();
?>

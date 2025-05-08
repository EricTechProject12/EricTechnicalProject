<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Get the selected role

    $sql = "SELECT password, role FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $row['role'];

            if ($row['role'] === 'stylist') {
                header("Location: stylist_dashboard.php");
            } elseif ($row['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $login_error = "Incorrect password!";
        }
    } else {
        $login_error = "Email not found!";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="signin.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            
            <select name="role" required>
                <option value="customer">Customer</option>
                <option value="stylist">Stylist</option>
                <option value="admin">Admin</option>
            </select><br>

            <button type="submit">Login</button>
        </form>

        <?php if ($login_error): ?>
            <p style="color: red;"><?php echo $login_error; ?></p>
        <?php endif; ?>

        <!-- Link to Sign Up page -->
        <p>Don't have an account? <a href="signup.php">Sign Up here</a></p>
    </div>
</body>
</html>

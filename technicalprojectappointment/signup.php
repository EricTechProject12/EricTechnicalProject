<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body class="signup-body">
    <div class="login-container">
        <h2>Signup</h2>
        <?php if (isset($_GET['error'])): ?>
    <div class="message-box error">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="message-box success">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php endif; 
?>

        <!-- Form submits to connect.php with POST method -->
        <form action="connect.php" method="post" id="signupForm">
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone_number" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm-password" placeholder="Confirm Password" required>

            <!-- Role selection dropdown -->
            <select name="role" required>
                <option value="customer">Customer</option>
                <option value="stylist">Stylist</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Signup</button>
        </form>

        <br>
        <!-- Link to signin page for existing users -->
        <a href="signin.php">
            <button type="button">Already have an account? Sign in here</button>
        </a>

        <p><a href="forgot-password.php" id="forgotPassword">Forgot Password?</a></p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="reset-style.css">
</head>
<body>
    <div class="form-container">
        <h2>Forgot Your Password?</h2>
        <p>No worries! Enter your email below and we’ll send you a link to reset it.</p>

        <form action="send-reset-email.php" method="post">
            <input type="email" name="email" placeholder="Your email address" required>
            <button type="submit">Send Reset Link</button>
        </form>

        <a class="back-link" href="signin.php">← Back to Login</a>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Your Password</title>
    <link rel="stylesheet" href="reset-style.css">
</head>
<body>
    <div class="form-container">
        <h2>Create a New Password</h2>
        <p>Enter your new password below.</p>

        <form action="update-password.php" method="post">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">

            <input type="password" name="new_password" placeholder="New password" required>
            <input type="password" name="confirm_password" placeholder="Confirm password" required>
            <button type="submit">Update Password</button>
        </form>
    </div>
</body>
</html>

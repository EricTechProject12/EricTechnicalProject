<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: signin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "user_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT id, name, price, image FROM services"); // Fetch 'id' column too
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nay's Hair Booking</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <h1>Welcome to Nay's Hair Booking!</h1>
    <p>You are now logged in <?php echo $_SESSION['user_email']; ?></p>
    <a href="logout.php">Logout</a>

    <div class="icon-container">
        <a href="profile.php">
            <i class="fas fa-user-circle"></i>
        </a>
    </div>

    <div class="hairstyle-gallery">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <a href="booking.php?service_id=<?php echo $row['id']; ?>"> <!-- Corrected -->
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p style='color:white;'>No hairstyles available at the moment.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>

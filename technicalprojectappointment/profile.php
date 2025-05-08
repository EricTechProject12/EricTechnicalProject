<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: signin.php");
    exit();
}

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['user_email'];

// SQL query to fetch bookings along with hairstyle name, price, and time
$sql = "
SELECT b.id, s.name AS hairstyle_name, s.price, b.booking_date, t.time AS booking_time
FROM bookings b
JOIN services s ON b.service_id = s.id
JOIN timeslots t ON b.time_slot_id = t.id
WHERE b.user_email = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="profile-container">
        <h1>Your Bookings</h1>

        <a href="dashboard.php" class="home-link">← Return to Home</a>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="booking-card">
                    <p><strong>Hairstyle:</strong> <?= htmlspecialchars($row['hairstyle_name']); ?></p>
                    <p><strong>Price:</strong> $<?= htmlspecialchars($row['price']); ?></p>
                    <p><strong>Date:</strong> <?= htmlspecialchars($row['booking_date']); ?></p>
                    <p><strong>Time:</strong> <?= htmlspecialchars($row['booking_time']); ?></p>
                    <a class="cancel-btn" href="cancel_booking.php?id=<?= urlencode($row['id']); ?>">Cancel Appointment</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-bookings">
                <p>No bookings found.</p>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Cancellation</title>
    <link rel="stylesheet" href="cancel_booking.css">
</head>
<body>
    <div class="cancel-container">
        <div class="cancel-box">
            <h1>Cancellation Successful</h1>
            <p class="success-message">Your appointment has been cancelled successfully.</p>
            <a href="booking.php">Go back to your bookings</a>
        </div>
    </div>
</body>
</html>


<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: signin.php");
    exit();
}

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "user_db";

    $conn = new mysqli($host, $user, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete the booking
    $sql = "DELETE FROM bookings WHERE id = '$booking_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Appointment cancelled successfully.</p>";
        echo "<p><a href='booking.php'>Go back to your bookings</a></p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }

    $conn->close();
} else {
    echo "<p>No booking ID provided.</p>";
}
?>

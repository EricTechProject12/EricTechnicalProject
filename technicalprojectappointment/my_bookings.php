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
$dbname = "salon_booking";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['user_email'];
$sql = "SELECT * FROM bookings WHERE user_email = '$user_email' ORDER BY booking_date, booking_time";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h1>My Bookings</h1>
    <table border="1">
        <tr>
            <th>Hairstyle</th>
            <th>Price</th>
            <th>Date</th>
            <th>Time</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['hairstyle_name']; ?></td>
                <td>$<?php echo $row['price']; ?></td>
                <td><?php echo $row['booking_date']; ?></td>
                <td><?php echo $row['booking_time']; ?></td>
            </tr>
        <?php } ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

<?php $conn->close(); ?>

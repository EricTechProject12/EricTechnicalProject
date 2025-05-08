<?php
session_start();
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'stylist') {
    header("Location: signin.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Corrected SQL: join services to get hairstyle_name and price
$sql = "SELECT b.user_email, s.name AS hairstyle_name, s.price, b.booking_date, b.booking_time
FROM bookings b
JOIN services s ON b.service_id = s.id
ORDER BY b.booking_date, b.booking_time";

$result = $conn->query($sql);

// Error check in case the query fails
if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stylist Dashboard</title>
    <link rel="stylesheet" href="stylist_dashboard.css">
</head>
<body>
    <h2>Stylist Dashboard - Confirm Bookings</h2>
    <table border="1">
        <tr>
            <th>Customer</th>
            <th>Hairstyle</th>
            <th>Price</th>
            <th>Date</th>
            <th>Time</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['user_email']); ?></td>
            <td><?php echo htmlspecialchars($row['hairstyle_name']); ?></td>
            <td>$<?php echo htmlspecialchars($row['price']); ?></td>
            <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
            <td><?php echo htmlspecialchars($row['booking_time']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="logout.php">Logout</a>
</body>
</html>

<?php
$conn->close();
?>

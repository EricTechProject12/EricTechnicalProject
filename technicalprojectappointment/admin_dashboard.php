<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete service or timeslot if requested
if (isset($_GET['delete_service'])) {
    $id = $_GET['delete_service'];
    $conn->query("DELETE FROM services WHERE id=$id");
}

if (isset($_GET['delete_slot'])) {
    $id = $_GET['delete_slot'];
    $conn->query("DELETE FROM timeslots WHERE id=$id");
}

// Fetch services
$services_result = $conn->query("SELECT * FROM services");

// Fetch time slots
$slots_result = $conn->query("SELECT * FROM timeslots");

// Fetch bookings
$bookings_result = $conn->query("SELECT * FROM bookings");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <div class="admin-container">
        <h1>Welcome, Admin</h1>

        <section>
            <h2>Manage Services</h2>
            <a href="add_service.php">Add New Service</a>
            <ul>
                <?php while ($row = $services_result->fetch_assoc()): ?>
                    <li>
                        <?php echo $row['name']; ?> - $<?php echo $row['price']; ?>
                        <a href="edit_service.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="?delete_service=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>

        <section>
            <h2>Manage Time Slots</h2>
            <a href="add_slot.php">Add New Time Slot</a>
            <ul>
                <?php while ($row = $slots_result->fetch_assoc()): ?>
                    <li>
                        <?php echo $row['date'] . " - " . $row['time']; ?>
                        <a href="edit_slot.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="?delete_slot=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>

        <section>
            <h2>View All Bookings</h2>
            <table>
                <tr>
                    <th>Email</th>
                    <th>Service</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = $bookings_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['user_email']; ?></td>
                        <td><?php echo $row['service']; ?></td>
                        <td><?php echo $row['booking_time']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </section>

        <br>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>
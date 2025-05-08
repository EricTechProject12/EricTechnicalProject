<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: signin.php");
    exit();
}

if (!isset($_GET['service_id'])) {
    header("Location: dashboard.php");
    exit();
}

$service_id = intval($_GET['service_id']); // security: only integers!

$conn = new mysqli("localhost", "root", "", "user_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the service details by ID
$stmt = $conn->prepare("SELECT name, price, image FROM services WHERE id = ?");
$stmt->bind_param("i", $service_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Service not found.";
    exit();
}

$service = $result->fetch_assoc();

// Fetch available time slots for this service
$timeslot_query = $conn->prepare("SELECT id, time FROM timeslots WHERE service_id = ?");
$timeslot_query->bind_param("i", $service_id);
$timeslot_query->execute();
$timeslot_result = $timeslot_query->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Appointment</title>
    <link rel="stylesheet" href="booking.css">
</head>
<body>
<div class="booking-container">
    <h1>Book Your Appointment</h1>
    <div class="hairstyle-info">
        <img src="<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['name']); ?>">
        <h2><?php echo htmlspecialchars($service['name']); ?></h2>
        <p>Price: $<?php echo htmlspecialchars($service['price']); ?></p>
    </div>

    <form action="confirm_booking.php" method="POST">
        <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">

        <label for="time_slot_id">Select Available Time:</label>
        <select id="time_slot_id" name="time_slot_id" required>
            <?php if ($timeslot_result->num_rows > 0): ?>
                <?php while($slot = $timeslot_result->fetch_assoc()): ?>
                    <option value="<?php echo $slot['id']; ?>"><?php echo date("h:i A", strtotime($slot['time'])); ?></option>
                <?php endwhile; ?>
            <?php else: ?>
                <option disabled>No available times.</option>
            <?php endif; ?>
        </select>

        <label for="booking_date">Select Date:</label>
        <input type="date" id="booking_date" name="booking_date" required>

        <button type="submit">Book Now</button>
    </form>

    <a href="dashboard.php" class="back-link">Go Back</a>
</div>
</body>
</html>
<?php
$conn->close();
?>

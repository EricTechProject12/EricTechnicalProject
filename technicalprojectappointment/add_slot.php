<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "user_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$services = $conn->query("SELECT id, name FROM services");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_id = $_POST['service_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $stmt = $conn->prepare("INSERT INTO timeslots (service_id, date, time) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $service_id, $date, $time);
    $stmt->execute();

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Time Slot</title>
    <link rel="stylesheet" href="form_style.css">
</head>
<body>
<h2>Add New Time Slot</h2>
<form method="POST">
    <label for="service_id">Select Service:</label>
    <select name="service_id" id="service_id" required>
        <?php while ($row = $services->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
        <?php endwhile; ?>
    </select><br>

    <label for="date">Select Date:</label>
    <input type="date" name="date" required><br>

    <label for="time">Select Time:</label>
    <input type="time" name="time" required><br>

    <button type="submit">Add Time Slot</button>
</form>
<br>
<a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>

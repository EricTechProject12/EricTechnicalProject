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

// Check if the id parameter exists in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No time slot ID specified.");
}

$id = intval($_GET['id']);  // Convert to integer for safety

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $conn->query("UPDATE timeslots SET date='$date', time='$time' WHERE id=$id");
    header("Location: admin_dashboard.php");
    exit();
}

$result = $conn->query("SELECT * FROM timeslots WHERE id=$id");
if(!$result || $result->num_rows === 0) {
    die("Error: Time slot not found.");
}

$slot = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Time Slot</title>
    <link rel="stylesheet" href="form_style.css">
</head>
<body>
    <h2>Edit Time Slot</h2>
    <form method="POST">
        <input type="date" name="date" value="<?php echo htmlspecialchars($slot['date']); ?>" required><br>
        <input type="time" name="time" value="<?php echo htmlspecialchars($slot['time']); ?>" required><br>
        <button type="submit">Update Time Slot</button>
    </form>
    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
<?php $conn->close(); ?>

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

$id = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $conn->query("UPDATE services SET name='$name', price='$price' WHERE id=$id");
    header("Location: admin_dashboard.php");
    exit();
}

$result = $conn->query("SELECT * FROM services WHERE id=$id");
$service = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head><title>Edit Service</title></head>
<body>
<h2>Edit Service</h2>
<link rel="stylesheet" href="form_style.css">
<form method="POST">
    <input type="text" name="name" value="<?php echo $service['name']; ?>" required><br>
    <input type="number" step="0.01" name="price" value="<?php echo $service['price']; ?>" required><br>
    <button type="submit">Update Service</button>
</form>
<br>
<a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serviceName = $_POST['service_name'];
    $price = $_POST['price'];
    $imageUrl = $_POST['service_image_url'];

    $stmt = $conn->prepare("INSERT INTO services (name, price, image) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sds", $serviceName, $price, $imageUrl);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error inserting record: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add New Service</title>
    <link rel="stylesheet" href="form_style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        .form-container {
            background: #fff;
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            color: #333;
        }
        .form-container form input,
        .form-container form button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .form-container form button {
            background-color: #2a9d8f;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .form-container form button:hover {
            background-color: #21867a;
        }
        .form-container a {
            display: inline-block;
            margin-top: 15px;
            text-align: center;
            color: #2a9d8f;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New Service</h2>
        <form method="POST">
            <label for="service_name">Service Name:</label>
            <input type="text" name="service_name" id="service_name" required>
            
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" required>
            
            <label for="service_image_url">Service Image URL:</label>
            <input type="url" name="service_image_url" id="service_image_url" placeholder="https://example.com/image.jpg" required>
            
            <button type="submit">Add Service</button>
        </form>
        <br>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
<?php
$conn->close();
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    header("Location: signup.php?error=" . urlencode("Connection failed. Please try again later."));
    exit();
}

// Get form data
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm-password'];
$role = $_POST['role'];

// Check if passwords match
if ($password !== $confirm_password) {
    header("Location: signup.php?error=" . urlencode("Passwords do not match!"));
    exit();
}

// Check if email already exists
$checkEmail = "SELECT email FROM users WHERE email = ?";
$stmt = $conn->prepare($checkEmail);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    header("Location: signup.php?error=" . urlencode("This email is already in use. Please try another."));
    exit();
}

$stmt->close();

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user into database
$insert = "INSERT INTO users (email, phone_number, password, role) VALUES (?, ?, ?, ?)";
$insertStmt = $conn->prepare($insert);
$insertStmt->bind_param("ssss", $email, $phone_number, $hashed_password, $role);

if ($insertStmt->execute()) {
    header("Location: signin.php?success=" . urlencode("Thank you for signing up! You can now log in."));
    exit();
} else {
    header("Location: signup.php?error=" . urlencode("Something went wrong. Please try again."));
    exit();
}
?>

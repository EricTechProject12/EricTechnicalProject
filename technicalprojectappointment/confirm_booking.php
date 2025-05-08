<?php
session_start();
if (!isset($_SESSION['user_email'])) {
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_SESSION['user_email'];
    $service_id = intval($_POST['service_id']);
    $time_slot_id = intval($_POST['time_slot_id']);
    $booking_date = $_POST['booking_date'];

    // Check if the selected time slot is already booked
    $check = $conn->prepare("SELECT id FROM bookings WHERE time_slot_id = ? AND booking_date = ?");
    $check->bind_param("is", $time_slot_id, $booking_date);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        echo "<h3>This time slot is already booked! Please go back and select another.</h3>";
    } else {
        // Fetch the actual time from the timeslots table
        $time_stmt = $conn->prepare("SELECT time FROM timeslots WHERE id = ?");
        $time_stmt->bind_param("i", $time_slot_id);
        $time_stmt->execute();
        $time_result = $time_stmt->get_result();

        if ($time_result->num_rows === 0) {
            echo "Invalid time slot selected.";
        } else {
            $time_row = $time_result->fetch_assoc();
            $booking_time = $time_row['time'];

            // Insert complete booking info
            $sql = $conn->prepare("INSERT INTO bookings (user_email, service_id, time_slot_id, booking_date, booking_time, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            $sql->bind_param("siiss", $user_email, $service_id, $time_slot_id, $booking_date, $booking_time);

            if ($sql->execute()) {
                echo "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Booking Confirmed</title>
                    <link rel='stylesheet' href='confirm_booking.css'>
                </head>
                <body>
                    <div class='booking-container'>
                        <h1>Booking Confirmation</h1>
                        <p>Your appointment has been successfully booked for <strong>" . htmlspecialchars($booking_date) . "</strong> at <strong>" . date("h:i A", strtotime($booking_time)) . "</strong>.</p>
                        <p>Thank you for choosing us!</p>
                        <a href='dashboard.php'>Back to Dashboard</a>
                    </div>
                </body>
                </html>";
            } else {
                echo "Error: " . $conn->error;
            }
            $sql->close();
        }
        $time_stmt->close();
    }
    $check->close();
}

$conn->close();
?>

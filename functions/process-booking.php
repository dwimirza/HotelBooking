<?php
session_start();
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $hotel_id = intval($_POST['hotel_id']);
    $room_id = intval($_POST['room_id']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $country_code = mysqli_real_escape_string($conn, $_POST['country_code']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $adult_count = intval($_POST['adult_count']);
    $child_count = intval($_POST['child_count']);
    $checkin_date = mysqli_real_escape_string($conn, $_POST['checkin_date']);
    $checkout_date = mysqli_real_escape_string($conn, $_POST['checkout_date']);
    $nights = intval($_POST['nights']);
    
    // Get room price
    $sql_room = "SELECT price FROM rooms WHERE room_id = ?";
    $stmt = $conn->prepare($sql_room);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
    
    $room_price = $room['price'];
    $tax = 150000;
    $total_price = ($room_price * $nights) + $tax;
    
    // Insert booking
    $sql_insert = "INSERT INTO bookings (hotel_id, room_id, guest_name, email, phone, adult_count, child_count, checkin_date, checkout_date, nights, total_price, booking_date) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt_insert = $conn->prepare($sql_insert);
    $full_phone = $country_code . $phone;
    $stmt_insert->bind_param("iisssiissid", $hotel_id, $room_id, $full_name, $email, $full_phone, $adult_count, $child_count, $checkin_date, $checkout_date, $nights, $total_price);
    
    if ($stmt_insert->execute()) {
        $booking_id = $conn->insert_id;
        // Redirect to payment page
        header("Location: payment.php?booking_id=" . $booking_id);
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    
    $conn->close();
} else {
    header("Location: hotels.html");
    exit();
}
?>
?>
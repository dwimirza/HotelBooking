<?php
include '../database.php';
// Unset all session variables
$_SESSION = array();

// Destroy the session
// session_unset();
// session_destroy();
session_start();


// input
$username = mysqli_real_escape_string($conn, $_POST['name']);
$password = $_POST['password_hash']; // Don't hash or escape password—verify instead!

// Get user by username only
$query = "SELECT * FROM users WHERE name collate utf8mb4_bin = '$username' LIMIT 1";
$result = $conn->query($query);

$data = null;
if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
    if ($data && isset($data['password_hash']) && password_verify($password, $data['password_hash'])) {
        // Store session data
        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['name'] = $data['name'];
        $_SESSION['email'] = $data['email'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['status'] = 'login';
        // Direct based on role
        if ($data['role'] == 'admin') {
            header("Location: ../admin/index.php");
            exit();
        } elseif ($data['role'] == 'user') {
            header("Location: ../Hotel-Booking-System/index.php?login=success");
            exit();
        } else {
            // Unknown role, redirect to front
            $_SESSION['error'] = "Unknown role!";
            header("Location: ../index.php");
            exit();
        }
    } else {
        // Password wrong
        $_SESSION['error'] = "Invalid username or password!";
        header("Location: ../Hotel-Booking-System/index.php?login=failed");
        exit();
    }
} else {
    // No user found
    $_SESSION['error'] = "Invalid username or password!";
    header("Location: ../Hotel-Booking-System/index.php?login=failed");
    exit();
}
?>

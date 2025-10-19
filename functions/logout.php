<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_unset();
session_destroy();

// Redirect to login or home page
header("Location: ../Hotel-Booking-System/index.php");
exit();
?>
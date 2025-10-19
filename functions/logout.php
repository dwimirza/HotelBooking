<?php
session_start();

<<<<<<< HEAD
// Hapus semua session
session_unset();
session_destroy();

header("Location: ../index.php");
exit();
?>
=======
// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login or home page
header("Location: login.php");
exit();
?>
>>>>>>> a1f8e3d30df41dec47899b0ffb65814281d0a79e

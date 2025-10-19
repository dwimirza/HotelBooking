<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

header("Location: ../index.php");
exit();
?>

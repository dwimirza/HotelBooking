<?php
session_start();
include '../database.php';

//input
$username = mysqli_real_escape_string($conn, $_POST['name']);
$password = mysqli_real_escape_string($conn,$_POST['password_hash']);
// Query user berdasarkan username dan password
$query = "SELECT * FROM users WHERE name collate utf8mb4_bin = '$username' AND password_hash = SHA('$password');";
$result = mysqli_query($conn, $query);

// Cek hasil query
if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);

    // Simpan data user ke session
    $_SESSION['user_id'] = $data['user_id']; 
    $_SESSION['name'] = $data['name'];
    $_SESSION['email'] = $data['email']; 
    $_SESSION['role'] = $data['role'];
    $_SESSION['status'] = 'login';

    // Arahkan berdasarkan role
    if ($data['role'] == 'admin') {
        echo "<script>window.location.href = '../admin/index.php';</script>";
        // header("Location: admin/index.php");
        exit();
    } elseif ($data['role'] == 'user') {
        // header("Location: ../Hotel-Booking-System/index.php");
        header("Location: ../Hotel-Booking-System/index.php?login=success");
        exit();
    } else {
        $_SESSION['error'] = "Role tidak dikenal!";
        header("Location: index.php");
        exit();
    }

} else {
    // Jika login gagal
    $_SESSION['error'] = "Username atau Password salah!";
    header("Location: ../Hotel-Booking-System/index.php?login=failed");
    exit();
}
?>
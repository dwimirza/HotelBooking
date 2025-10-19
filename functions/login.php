<?php
session_start();
include '../database.php';

// input
$username = mysqli_real_escape_string($conn, $_POST['name']);
$password = $_POST['password_hash']; // Don't hash or escape password—verify instead!

// Get user by username only
$query = "SELECT * FROM users WHERE name collate utf8mb4_bin = '$username' LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);

<<<<<<< HEAD
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
=======
    // Verify password with password_verify
    if (password_verify($password, $data['password_hash'])) {
        // Simpan data user ke session
        $_SESSION['name'] = $data['name'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['status'] = 'login';

        // Arahkan berdasarkan role
        if ($data['role'] == 'admin') {
            echo "<script>window.location.href = '../admin/index.php';</script>";
            exit();
        } elseif ($data['role'] == 'user') {
            header("Location: ../Hotel-Booking-System/index.html");
            exit();
        } else {
            $_SESSION['error'] = "Role tidak dikenal!";
            header("Location: index.php");
            exit();
        }
>>>>>>> a1f8e3d30df41dec47899b0ffb65814281d0a79e
    } else {
        // Jika password salah
        $_SESSION['error'] = "Username atau Password salah!";
        header("Location: ../index.php");
        exit();
    }
} else {
    // Jika username tidak ditemukan
    $_SESSION['error'] = "Username atau Password salah!";
    header("Location: ../Hotel-Booking-System/index.php?login=failed");
    exit();
}
?>

<?php
session_start();
include '../database.php'; // Adjust this path to your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $username = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password_hash'];

    // Simple validation
    if (!$username || !$email || !$password) {
        $_SESSION['error'] = 'All fields are required!';
        header('Location: ../register.php'); // Adjust path
        exit();
    }

    // Check if user already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param('s', $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Email already exists!';
        header('Location: ../register.php');
        exit();
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    // Insert user to database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 1)");
    $stmt->bind_param('sss', $username, $email, $password_hash);

    if ($stmt->execute()) {
        $_SESSION['error'] = ''; // Clear error
        header('Location: ../Hotel-Booking-System/index.php'); // Redirect to login after success
        exit();
    } else {
        $_SESSION['error'] = 'Registration failed. Try again!';
        header('Location: ../register.php');
        exit();
    }
}
else {
    // If not POST, go to register page
    header('Location: ../register.php');
    exit();
}
?>

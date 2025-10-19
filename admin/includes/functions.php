<?php
include_once 'database.php';
function getUserCount($conn) {
    $sql = "SELECT COUNT(*) AS count FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

function getTransactionHistory($conn, $userId = null) {
    if ($userId) {
        $sql = "SELECT * FROM payments WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
    } else {
        $sql = "SELECT * FROM payments order by payment_date desc LIMIT 5";
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $data;
}

function getUsers($conn) {
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    return $data;
}
function getPayments($conn) {
    $sql = "SELECT * FROM payments";
    $result = $conn->query($sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    return $data;
}


function getHotelsByCity($conn) {
    $sql = "select city, count(*) as count from hotels group by city asc";
    $result = $conn->query($sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    return $data;
}

function getPaymentCount($conn) {
    $sql = "SELECT COUNT(*) AS count FROM payments";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

?>
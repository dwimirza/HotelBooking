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
        $sql = "SELECT * FROM payments";
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $data;
}

?>
<?php
include_once 'database.php';
function getUserCount($conn) {
    $sql = "SELECT COUNT(*) AS count FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

?>
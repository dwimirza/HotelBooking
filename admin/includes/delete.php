<?php 
include_once 'database.php';
if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = intval($_GET['id']);
    if ($type == 'report') {
        // Call function or SQL to delete from reports table
    } elseif ($type == 'hotel') {
        echo "masuk $id";
        $sql = "DELETE FROM hotels WHERE hotel_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
            if($stmt->affected_rows > 0){
                $stmt->close();
                header("Location: ../Hotel.php");
                exit();
            } else {
                $stmt->close();
                header("Location: ../Hotel.php");
                exit(); 
            }
        }
    }
?>
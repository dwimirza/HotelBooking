<?php 
include_once 'database.php';
if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = intval($_GET['id']);
    if ($type == 'room') {
        $sql = "DELETE FROM rooms WHERE room_id = ?";
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
         elseif ($type == 'hotel') {
        // First, delete from hotel_facilities
        $sql1 = "DELETE FROM hotel_facilities WHERE hotel_id = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
        $stmt1->close();

        // Then, delete from hotels
        $sql2 = "DELETE FROM hotels WHERE hotel_id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $stmt2->close();

        // Redirect after deletion
        header("Location: ../Hotel.php");
        exit();
        }
    }
?>
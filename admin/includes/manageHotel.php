<?php
include_once 'database.php';
function insertHotel($conn) {
    $sql = "INSERT COUNT(*) AS count FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

if (isset($_GET['action']) && $_GET['action'] === 'getHotelData' && isset($_GET['id'])) {
    $hotelId = intval($_GET['id']);
    $hotelData = getHotels($conn, $hotelId);
    header('Content-Type: application/json');
    echo json_encode($hotelData);
    exit();
}

function getHotels($conn, $hotelId = null) {
    if ($hotelId) {
        $sql = "SELECT * FROM hotels WHERE hotel_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $hotelId);
    } else {
        $sql = "SELECT * FROM hotels";
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
        return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateHotel') {
    $hotelId = intval($_POST['hotelId']);
    $name    = $_POST['name'];
    $address = $_POST['address'];
    $phoneNo = $_POST['phoneNo'];
    $email   = $_POST['email'];
    $starRating = intval($_POST['starRating']);
    $result = updateHotel($conn, $hotelId, $name, $address, $phoneNo, $email, $starRating);

    header('Content-Type: application/json');
    echo json_encode(['success' => $result]);
    exit;
}

function updateHotel($conn, $hotelId, $name, $address, $phoneNo, $email, $starRating) {
    $sql = "UPDATE hotels SET hotel_name = ?, address = ?, phone_no = ?, email = ?, star_rating = ? WHERE hotel_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $name, $address, $phoneNo, $email, $starRating, $hotelId);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addHotel') {
    echo 'masuk';
    $name    = $_POST['hotel_name'];
    $address = $_POST['address'];
    $phoneNo = $_POST['phone_no'];
    $email   = $_POST['email'];
    $starRating = intval($_POST['star_rating']);
    $city = $_POST['city'];
    $result = addHotel($conn, $name, $address, $phoneNo, $email, $starRating, $city);
    echo $result ? 'success' : 'error';
    exit;
}

function addHotel($conn, $name, $address, $phoneNo, $email, $starRating, $city ) {
    $sql = "INSERT INTO hotels (hotel_name, address, phone_no, email, star_rating, city) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssis", $name, $address, $phoneNo, $email, $starRating, $city);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'getRoomData' && isset($_GET['id'])) {
    $hotelId = intval($_GET['id']);
    $roomData = getRooms($conn, $hotelId);
    header('Content-Type: application/json');
    echo json_encode($roomData);
    exit();
}

function getRooms($conn, $hotelId = null) {
    $sql = "SELECT * FROM rooms WHERE hotel_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hotelId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
        return $data;
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addRoom') {
    $hotelId    = $_POST['hotel_id'];
    $roomType = $_POST['room_type'];
    $price = $_POST['price'];
    $availability   = intval($_POST['availability']);
    $result = addRooms($conn, $hotelId, $roomType, $price, $availability);
    header('Content-Type: application/json');
    echo json_encode($result);
    
    exit;
}

function addRooms($conn, $hotelId, $roomType, $price, $availability ) {
    $sql = "INSERT INTO rooms (hotel_id, room_type, price, availability) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit;
    }
    $stmt->bind_param("isii", $hotelId, $roomType, $price, $availability);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateRoom') {
    $hotelId = intval($_POST['hotel_id']);
    $roomId = intval($_POST['room_id']);
    $roomType = $_POST['room_type'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];
    $result = updateRoom($conn, $roomType, $price, $availability, $hotelId, $roomId);

    header('Content-Type: application/json');
    echo json_encode(['success' => $result]);
    exit;
}

function updateRoom($conn, $roomType, $price, $availability, $hotelId, $roomId) {
    $sql = "UPDATE rooms SET room_type = ?, price = ?, availability = ? WHERE hotel_id = ? AND room_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiii", $roomType, $price, $availability, $hotelId, $roomId);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

?>
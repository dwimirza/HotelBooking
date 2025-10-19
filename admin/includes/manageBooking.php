<?php
include_once 'database.php';
function insertHotel($conn) {
    $sql = "INSERT COUNT(*) AS count FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

if (isset($_GET['action']) && $_GET['action'] === 'getBookingData' && isset($_GET['id'])) {
    $bookingId = intval($_GET['id']);
    $bookingData = getBookings($conn, $bookingId);
    header('Content-Type: application/json');
    echo json_encode(array_values($bookingData));
    exit();
}

function getBookings($conn, $bookingId = null) {
    if ($bookingId) {
        $sql = "
                select b.booking_id, u.name,  hotel_name, booking_date, status, total_amount, r.room_type, d.price_per_night , d.check_in, d.check_out from bookings b
                left join users u on u.user_id = b.user_id
                left join hotels h on h.hotel_id = b.hotel_id
                left join booking_details d on d.booking_id = b.booking_id
                left join rooms r on r.room_id = d.room_id
                where b.booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bookingId);
    } else {
        $sql = "
                select b.booking_id, u.name,  hotel_name, booking_date, status, total_amount, r.room_type, d.price_per_night , d.check_in, d.check_out from bookings b
                left join users u on u.user_id = b.user_id
                left join hotels h on h.hotel_id = b.hotel_id
                left join booking_details d on d.booking_id = b.booking_id
                left join rooms r on r.room_id = d.room_id ";
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $booking_id = $row['booking_id'];
        if (!isset($data[$booking_id])) {
            $data[$booking_id] = [
                'booking_id' => $row['booking_id'],
                'name' => $row['name'],
                'hotel_name' => $row['hotel_name'],
                'booking_date' => $row['booking_date'],
                'status' => $row['status'],
                'total_amount' => $row['total_amount'],
                'details' => []
            ];
        }
        if ($row['room_type']) {
            $data[$booking_id]['details'][] = [
                'room_type' => $row['room_type'],
                'price_per_night' => $row['price_per_night'],
                'check_in' => $row['check_in'],
                'check_out' => $row['check_out']
            ];
        }
    }
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
    $facilities = [
        'swimming_pool' => isset($_POST['swimming_pool']) ? intval($_POST['swimming_pool']) : 0,
        'gymnasium' => isset($_POST['gymnasium']) ? intval($_POST['gymnasium']) : 0,
        'wifi' => isset($_POST['wifi']) ? intval($_POST['wifi']) : 0,
        'room_service' => isset($_POST['room_service']) ? intval($_POST['room_service']) : 0,
        'air_condition' => isset($_POST['air_condition']) ? intval($_POST['air_condition']) : 0,
        'breakfast' => isset($_POST['breakfast']) ? intval($_POST['breakfast']) : 0
    ];
    $result = updateHotel($conn, $hotelId, $name, $address, $phoneNo, $email, $starRating, $facilities);

    header('Content-Type: application/json');
    echo json_encode(['success' => $result]);
    exit;
}

function updateHotel($conn, $hotelId, $name, $address, $phoneNo, $email, $starRating, $facilities = []) {
    $conn->autocommit(FALSE);
    try {
        // Update hotel table
        $sql = "UPDATE hotels SET hotel_name = ?, address = ?, phone_no = ?, email = ?, star_rating = ? WHERE hotel_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $name, $address, $phoneNo, $email, $starRating, $hotelId);

        if (!$stmt->execute()) {
            throw new Exception("Error updating hotel: " . $stmt->error);
        }
        $stmt->close();

        // Update facilities table
        $facilitySql = "UPDATE hotel_facilities SET swimming_pool=?, gymnasium=?, wifi=?, room_service=?, air_condition=?, breakfast=? WHERE hotel_id=?";
        $facilityStmt = $conn->prepare($facilitySql);
        $facilityStmt->bind_param("iiiiiii",
            $facilities['swimming_pool'],
            $facilities['gymnasium'],
            $facilities['wifi'],
            $facilities['room_service'],
            $facilities['air_condition'],
            $facilities['breakfast'],
            $hotelId
        );
        if (!$facilityStmt->execute()) {
            throw new Exception("Error updating facilities: " . $facilityStmt->error);
        }
        $facilityStmt->close();

        $conn->commit();
        $conn->autocommit(TRUE);
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        $conn->autocommit(TRUE);
        error_log("UpdateHotel Exception: " . $e->getMessage());
        return false;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addHotel') {
    // Basic hotel information
    $name = $_POST['hotel_name'];
    $address = $_POST['address'];
    $phoneNo = $_POST['phone_no'];
    $email = $_POST['email'];
    $starRating = intval($_POST['star_rating']);
    $city = $_POST['city'];

    // Facilities, using names that match the DB columns
    $facilities = [
        'swimming_pool' => isset($_POST['swimming_pool']) ? intval($_POST['swimming_pool']) : 0,
        'gymnasium' => isset($_POST['gymnasium']) ? intval($_POST['gymnasium']) : 0,
        'wifi' => isset($_POST['wifi']) ? intval($_POST['wifi']) : 0,
        'room_service' => isset($_POST['room_service']) ? intval($_POST['room_service']) : 0,
        'air_condition' => isset($_POST['air_condition']) ? intval($_POST['air_condition']) : 0,
        'breakfast' => isset($_POST['breakfast']) ? intval($_POST['breakfast']) : 0
    ];

    $result = addHotel($conn, $name, $address, $phoneNo, $email, $starRating, $city, $facilities);
    header('Location: Hotel.php');
    exit;
}

function addHotel($conn, $name, $address, $phoneNo, $email, $starRating, $city, $facilities = []) {
    $conn->autocommit(FALSE);

    try {
        // Insert hotel info
        $hotelSql = "INSERT INTO hotels (hotel_name, address, phone_no, email, star_rating, city) VALUES (?, ?, ?, ?, ?, ?)";
        $hotelStmt = $conn->prepare($hotelSql);
        $hotelStmt->bind_param("ssssis", $name, $address, $phoneNo, $email, $starRating, $city);

        if (!$hotelStmt->execute()) {
            throw new Exception("Hotel insert failed: " . $hotelStmt->error);
        }

        $hotelId = $conn->insert_id;
        $hotelStmt->close();

        // Insert facilities
        $facilitySql = "INSERT INTO hotel_facilities
            (hotel_id, swimming_pool, gymnasium, wifi, room_service, air_condition, breakfast)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $facilityStmt = $conn->prepare($facilitySql);

        $facilityStmt->bind_param("iiiiiii",
            $hotelId,
            $facilities['swimming_pool'],
            $facilities['gymnasium'],
            $facilities['wifi'],
            $facilities['room_service'],
            $facilities['air_condition'],
            $facilities['breakfast']
        );

        if (!$facilityStmt->execute()) {
            throw new Exception("Facilities insert failed: " . $facilityStmt->error);
        }

        $facilityStmt->close();

        // Commit if all succeeded
        $conn->commit();
        $conn->autocommit(TRUE);
        header('Location: Hotel.php');
        return true;

    } catch (Exception $e) {
        $conn->rollback();
        $conn->autocommit(TRUE);
        error_log("Error in addHotel: " . $e->getMessage());
        return false;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'getDetailData' && isset($_GET['id'])) {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) == 'updateBooking' && isset($_POST['status'])) {
    $bookingId = intval($_POST['booking_id']);
    $status = htmlspecialchars($_POST['status']);

    // Validate status
    $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
    if (!in_array($status, $validStatuses)) {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        exit;
    }

    // Update status
    $result = updateBookingStatus($conn, $bookingId, $status);
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }
}
function updateBookingStatus($conn, $bookingId, $status) {
    $sql = "UPDATE bookings SET status = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $bookingId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

?>
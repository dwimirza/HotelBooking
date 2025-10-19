<?php 
include_once 'database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) == 'updatePayment' && isset($_POST['status'])) {
    $paymentId = intval($_POST['payment_id']);
    $status = htmlspecialchars($_POST['status']);

    // Validate status
    $validStatuses = ['paid', 'pending', 'failed', 'refunded'];
    if (!in_array($status, $validStatuses)) {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        exit;
    }

    // Update status
    $result = updatePaymentStatus($conn, $paymentId, $status);
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

function updatePaymentStatus($conn, $bookingId, $status) {
    $sql = "UPDATE payments SET status = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $status, $bookingId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

?>
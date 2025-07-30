<?php
include_once "../config/dbconnect.php";

if (!isset($_GET['id'])) {
    header('Location: ../adminView/viewAllrooms.php?error=missing_id');
    exit();
}

$id = intval($_GET['id']);

// Check if there are any reservations for this room
$check_reservations_sql = "SELECT COUNT(*) as count FROM reservation WHERE room_id = ?";
$stmt = $conn->prepare($check_reservations_sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$reservation_count = $result->fetch_assoc()['count'];

// If there are reservations and no confirmation, show warning
if ($reservation_count > 0 && !isset($_GET['confirm'])) {
    header('Location: ../adminView/viewAllrooms.php?warning=has_reservations&room_id=' . $id . '&count=' . $reservation_count);
    exit();
}

// Start transaction to ensure data consistency
$conn->begin_transaction();

try {
    // First, delete all reservations associated with this room
    if ($reservation_count > 0) {
        $delete_reservations_sql = "DELETE FROM reservation WHERE room_id = ?";
        $stmt = $conn->prepare($delete_reservations_sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
    
    // Then delete the room
    $delete_room_sql = "DELETE FROM room WHERE room_id = ?";
    $stmt = $conn->prepare($delete_room_sql);
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        // Commit the transaction
        $conn->commit();
        $message = $reservation_count > 0 ? 
            "Room deleted successfully. {$reservation_count} related reservation(s) were also deleted." : 
            "Room deleted successfully.";
        header('Location: ../adminView/viewAllrooms.php?success=1&message=' . urlencode($message));
    } else {
        // Rollback on error
        $conn->rollback();
        header('Location: ../adminView/viewAllrooms.php?error=db');
    }
} catch (Exception $e) {
    // Rollback on any exception
    $conn->rollback();
    header('Location: ../adminView/viewAllrooms.php?error=constraint');
}

exit(); 
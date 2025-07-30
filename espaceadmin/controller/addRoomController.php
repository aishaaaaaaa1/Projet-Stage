<?php
include_once "../config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    $room_number = $_POST['room_number'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $status = 'Disponible'; // Statut par défaut
    
    // Vérifier si le numéro de chambre existe déjà
    $check_sql = "SELECT room_id FROM room WHERE room_number = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('s', $room_number);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        header('Location: ../adminView/viewAllrooms.php?error=room_exists');
        exit();
    }
    
    // Gestion de l'upload de photo
    $photo_path = null;
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "../uploads/";
        $file_name = basename($_FILES['room_image']['name']);
        $upload_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['room_image']['tmp_name'], $upload_path)) {
            $photo_path = '../uploads/' . $file_name;
        }
    }
    
    // Insérer la nouvelle chambre avec la catégorie
    $sql = "INSERT INTO room (room_number, description, price, category_id, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssdsss', $room_number, $description, $price, $category_id, $photo_path, $status);
    
    if ($stmt->execute()) {
        header('Location: ../adminView/viewAllrooms.php?success=1');
    } else {
        header('Location: ../adminView/viewAllrooms.php?error=db');
    }
    exit();
} else {
    header('Location: ../adminView/viewAllrooms.php?error=invalid_request');
    exit();
}
?> 
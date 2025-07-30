<?php
include_once "../config/dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider et récupérer les données
    $room_id = filter_input(INPUT_POST, 'room_id', FILTER_VALIDATE_INT);
    $room_number = filter_input(INPUT_POST, 'room_number', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    if (!$room_id || !$room_number || !$price || !$category_id) {
        http_response_code(400); // Bad Request
        echo "Données invalides.";
        exit();
    }

    // Préparer la requête SQL
    $sql = "UPDATE room SET 
                room_number = ?, description = ?, price = ?, 
                category_id = ?, status = ? 
            WHERE room_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisi", $room_number, $description, $price, $category_id, $status, $room_id);
    
    // Exécuter et renvoyer une réponse
    if ($stmt->execute()) {
        http_response_code(200); // OK
        echo "Mise à jour réussie.";
    } else {
        http_response_code(500); // Internal Server Error
        echo "Erreur lors de la mise à jour.";
    }
    
    $stmt->close();
    $conn->close();
}
?> 
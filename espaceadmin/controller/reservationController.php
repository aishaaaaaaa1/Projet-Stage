<?php
include_once "../config/dbconnect.php";
include_once "../config/paths.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $reservation_id = intval($_GET['id']);
    
    // Récupérer les informations de la réservation
    $sql = "SELECT r.*, rm.price, u.id as user_id 
            FROM reservation r 
            LEFT JOIN room rm ON r.room_id = rm.room_id 
            LEFT JOIN users u ON r.user_id = u.id 
            WHERE r.reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reservation = $result->fetch_assoc();
    
    if (!$reservation) {
        header('Location: ../adminView/viewReservations.php?error=not_found');
        exit();
    }
    
    if ($action === 'accept') {
        // Accepter la réservation
        $update_sql = "UPDATE reservation SET status = 'Acceptée' WHERE reservation_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('i', $reservation_id);
        
        if ($update_stmt->execute()) {
            // Calculer le prix total (prix par mois * nombre de mois)
            $prix_total = $reservation['price'] * ($reservation['nb_mois'] ?? 1);
            
            // Ajouter le prix de la chambre à la facture mensuelle de l'utilisateur
            $facture_sql = "INSERT INTO factures (id_etudiant, montant, description, date_creation, date_echeance, statut, created_at, updated_at) 
                           VALUES (?, ?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'En attente', NOW(), NOW())";
            $facture_stmt = $conn->prepare($facture_sql);
            $description = "Réservation chambre " . $reservation['room_id'] . " - " . $reservation['date_reservation'] . " (" . ($reservation['nb_mois'] ?? 1) . " mois)";
            $facture_stmt->bind_param('ids', $reservation['user_id'], $prix_total, $description);
            $facture_stmt->execute();
            
            header('Location: ../adminView/viewReservations.php?success=accepted');
        } else {
            header('Location: ../adminView/viewReservations.php?error=update_failed');
        }
        exit();
        
    } elseif ($action === 'reject') {
        // Refuser la réservation
        $update_sql = "UPDATE reservation SET status = 'Refusée' WHERE reservation_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('i', $reservation_id);
        
        if ($update_stmt->execute()) {
            header('Location: ../adminView/viewReservations.php?success=rejected');
        } else {
            header('Location: ../adminView/viewReservations.php?error=update_failed');
        }
        exit();
        
    } else {
        header('Location: ../adminView/viewReservations.php?error=invalid_action');
        exit();
    }
} else {
    header('Location: ../adminView/viewReservations.php?error=invalid_request');
    exit();
}
?> 
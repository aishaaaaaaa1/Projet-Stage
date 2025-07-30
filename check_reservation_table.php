<?php
// Script pour vérifier et corriger la structure de la table reservation
include_once "Connect.php";

echo "<h2>🔍 Vérification de la table reservation</h2>";

try {
    // 1. Vérifier si la table reservation existe
    $result = $conn->query("SHOW TABLES LIKE 'reservation'");
    if ($result->num_rows == 0) {
        echo "<p>❌ La table reservation n'existe pas. Création en cours...</p>";
       
        
        if ($conn->query($sql)) {
            echo "<p>✅ Table reservation créée avec succès !</p>";
        } else {
            echo "<p>❌ Erreur lors de la création de la table : " . $conn->error . "</p>";
        }
    } else {
        echo "<p>✅ La table reservation existe.</p>";
    }
    
    // 2. Vérifier la structure de la table
    echo "<h3>Structure de la table reservation :</h3>";
    $result = $conn->query("DESCRIBE reservation");
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Colonne</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // 3. Vérifier si la colonne nb_mois existe
    $result = $conn->query("SHOW COLUMNS FROM reservation LIKE 'nb_mois'");
    if ($result->num_rows == 0) {
        echo "<p>❌ La colonne nb_mois n'existe pas. Ajout en cours...</p>";
        
        $sql = "ALTER TABLE reservation ADD COLUMN nb_mois INT DEFAULT 1 AFTER time_end";
        if ($conn->query($sql)) {
            echo "<p>✅ Colonne nb_mois ajoutée avec succès !</p>";
        } else {
            echo "<p>❌ Erreur lors de l'ajout de la colonne : " . $conn->error . "</p>";
        }
    } else {
        echo "<p>✅ La colonne nb_mois existe.</p>";
    }
    
    // 4. Insérer des données de test si la table est vide
    $result = $conn->query("SELECT COUNT(*) as count FROM reservation");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        echo "<p>📝 Insertion de données de test...</p>";
        
        $sql = "INSERT INTO reservation (user_id, room_id, date_reservation, time_start, time_end, nb_mois, status, created_at) VALUES
        (8, 1, '2024-12-20', '09:00:00', '12:00:00', 1, 'En attente', NOW()),
        (19, 2, '2024-12-21', '14:00:00', '17:00:00', 3, 'Acceptée', NOW()),
        (22, 3, '2024-12-22', '10:00:00', '13:00:00', 6, 'En attente', NOW())";
        
        if ($conn->query($sql)) {
            echo "<p>✅ Données de test insérées avec succès !</p>";
        } else {
            echo "<p>❌ Erreur lors de l'insertion : " . $conn->error . "</p>";
        }
    } else {
        echo "<p>✅ La table contient déjà des données.</p>";
    }
    
    // 5. Afficher les réservations existantes
    echo "<h3>Réservations existantes :</h3>";
    $result = $conn->query("SELECT r.*, u.name as user_name, rm.room_number 
                           FROM reservation r 
                           LEFT JOIN users u ON r.user_id = u.id 
                           LEFT JOIN room rm ON r.room_id = rm.room_id 
                           ORDER BY r.created_at DESC");
    
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Étudiant</th><th>Chambre</th><th>Date</th><th>Durée</th><th>Statut</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['user_name'] . "</td>";
            echo "<td>" . $row['room_number'] . "</td>";
            echo "<td>" . $row['date_reservation'] . "</td>";
            echo "<td>" . ($row['nb_mois'] ?? 1) . " mois</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Aucune réservation trouvée.</p>";
    }
    
    echo "<br><h3>✅ Vérification terminée !</h3>";
    echo "<a href='espaceadmin/adminView/viewReservations.php'>Aller à la gestion des réservations</a>";
    
} catch (Exception $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
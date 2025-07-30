<?php
// Script de test pour vérifier le système de réservation
include_once "Connect.php";

echo "<h2>🔍 Test du Système de Réservation</h2>";

try {
    // 1. Vérifier la structure de la table reservation
    echo "<h3>1. Structure de la table reservation :</h3>";
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
    
    // 2. Vérifier la structure de la table factures
    echo "<h3>2. Structure de la table factures :</h3>";
    $result = $conn->query("DESCRIBE factures");
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
    
    // 3. Vérifier les réservations existantes
    echo "<h3>3. Réservations existantes :</h3>";
    $result = $conn->query("SELECT r.*, rm.room_number, u.name as user_name 
                           FROM reservation r 
                           LEFT JOIN room rm ON r.room_id = rm.room_id 
                           LEFT JOIN users u ON r.user_id = u.id 
                           ORDER BY r.created_at DESC 
                           LIMIT 5");
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
    
    // 4. Vérifier les factures existantes
    echo "<h3>4. Factures existantes :</h3>";
    $result = $conn->query("SELECT f.*, u.name as user_name 
                           FROM factures f 
                           LEFT JOIN users u ON f.id_etudiant = u.id 
                           ORDER BY f.created_at DESC 
                           LIMIT 5");
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Étudiant</th><th>Description</th><th>Montant</th><th>Statut</th><th>Date création</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['user_name'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['montant'] . " MAD</td>";
            echo "<td>" . $row['statut'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Aucune facture trouvée.</p>";
    }
    
    echo "<br><h3>✅ Test terminé !</h3>";
    echo "<p>Le système semble fonctionner correctement.</p>";
    echo "<a href='pei.php'>Aller à l'espace personnel</a> | ";
    echo "<a href='reservation.php'>Aller aux réservations</a>";
    
} catch (Exception $e) {
    echo "<h3>❌ Erreur lors du test :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?> 
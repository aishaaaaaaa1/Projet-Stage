<?php
// Script pour vérifier et corriger les contraintes de clés étrangères
include_once "Connect.php";

echo "<h2>🔧 Maintenance de la base de données - Contraintes de clés étrangères</h2>";

try {
    // 1. Vérifier les contraintes existantes
    echo "<h3>📋 Contraintes de clés étrangères existantes :</h3>";
    $sql = "SELECT 
                CONSTRAINT_NAME,
                TABLE_NAME,
                COLUMN_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
            AND REFERENCED_TABLE_NAME IS NOT NULL";
    
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Contrainte</th><th>Table</th><th>Colonne</th><th>Table Référencée</th><th>Colonne Référencée</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['CONSTRAINT_NAME'] . "</td>";
            echo "<td>" . $row['TABLE_NAME'] . "</td>";
            echo "<td>" . $row['COLUMN_NAME'] . "</td>";
            echo "<td>" . $row['REFERENCED_TABLE_NAME'] . "</td>";
            echo "<td>" . $row['REFERENCED_COLUMN_NAME'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Aucune contrainte de clé étrangère trouvée.</p>";
    }
    
    // 2. Vérifier les orphelins dans la table reservation
    echo "<h3>🔍 Vérification des réservations orphelines :</h3>";
    $sql = "SELECT COUNT(*) as count FROM reservation r 
            LEFT JOIN room rm ON r.room_id = rm.room_id 
            WHERE rm.room_id IS NULL";
    $result = $conn->query($sql);
    $orphan_count = $result->fetch_assoc()['count'];
    
    if ($orphan_count > 0) {
        echo "<p>⚠️ Trouvé {$orphan_count} réservation(s) avec des room_id invalides.</p>";
        
        if (isset($_GET['fix_orphans'])) {
            $delete_sql = "DELETE FROM reservation WHERE room_id NOT IN (SELECT room_id FROM room)";
            if ($conn->query($delete_sql)) {
                echo "<p>✅ Réservations orphelines supprimées avec succès !</p>";
            } else {
                echo "<p>❌ Erreur lors de la suppression : " . $conn->error . "</p>";
            }
        } else {
            echo "<p><a href='?fix_orphans=1' class='btn btn-warning'>Supprimer les réservations orphelines</a></p>";
        }
    } else {
        echo "<p>✅ Aucune réservation orpheline trouvée.</p>";
    }
    
    // 3. Statistiques des réservations par chambre
    echo "<h3>📊 Statistiques des réservations par chambre :</h3>";
    $sql = "SELECT rm.room_number, COUNT(r.id) as reservation_count 
            FROM room rm 
            LEFT JOIN reservation r ON rm.room_id = r.room_id 
            GROUP BY rm.room_id, rm.room_number 
            ORDER BY reservation_count DESC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Chambre</th><th>Nombre de réservations</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['room_number'] . "</td>";
            echo "<td>" . $row['reservation_count'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // 4. Options de maintenance
    echo "<h3>🛠️ Options de maintenance :</h3>";
    echo "<p><a href='?check_constraints=1' class='btn btn-info'>Vérifier les contraintes</a> ";
    echo "<a href='?fix_orphans=1' class='btn btn-warning'>Nettoyer les orphelins</a> ";
    echo "<a href='espaceadmin/adminView/viewAllrooms.php' class='btn btn-primary'>Retour à la gestion des chambres</a></p>";
    
} catch (Exception $e) {
    echo "<p>❌ Erreur : " . $e->getMessage() . "</p>";
}

echo "<br><hr><br>";
echo "<p><strong>💡 Conseil :</strong> Pour éviter les erreurs de contraintes de clés étrangères, assurez-vous de toujours supprimer les enregistrements enfants avant de supprimer les enregistrements parents.</p>";
?> 
<?php
include_once "../config/dbconnect.php";
include_once "../config/paths.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Réservations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>


<body>
    <?php 
        $base_path = dirname(__DIR__);
        include $base_path . "/adminHeader.php"; 
        include $base_path . "/sidebar.php"; 
    ?>

    <div class="container mt-4">
        <h2>Gestion des Réservations</h2>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">Réservation mise à jour avec succès !</div>
        <?php endif; ?>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger">Erreur lors de la mise à jour de la réservation.</div>
        <?php endif; ?>

        <!-- Tableau des réservations -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Étudiant</th>
                    <th>Chambre</th>
                    <th>Date</th>
                    <th>Heure début</th>
                    <th>Heure fin</th>
                    <th>Durée</th>
                    <th>Prix total</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Requête pour récupérer les réservations avec les informations des utilisateurs et chambres
                $sql = "SELECT r.reservation_id, r.user_id, r.room_id, r.date_reservation, r.time_start, r.time_end, r.nb_mois, r.status, r.created_at, u.name as user_name, rm.room_number, rm.price 
                        FROM reservation r 
                        LEFT JOIN users u ON r.user_id = u.id 
                        LEFT JOIN room rm ON r.room_id = rm.room_id 
                        ORDER BY r.created_at DESC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $status_class = '';
                        switch($row['status']) {
                            case 'En attente':
                                $status_class = 'badge-warning';
                                break;
                            case 'Acceptée':
                                $status_class = 'badge-success';
                                break;
                            case 'Refusée':
                                $status_class = 'badge-danger';
                                break;
                            default:
                                $status_class = 'badge-secondary';
                        }
                        
                        echo "<tr>";
                        echo "<td>" . intval($row['reservation_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['room_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['date_reservation']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['time_start']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['time_end']) . "</td>";
                        echo "<td>" . ($row['nb_mois'] ?? 1) . " mois</td>";
                        echo "<td>" . (($row['price'] ?? 0) * ($row['nb_mois'] ?? 1)) . " MAD</td>";
                        echo "<td><span class='badge " . $status_class . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                        echo "<td>";
                        
                        if ($row['status'] == 'En attente') {
                            echo "<a href='" . getControllerPath() . "reservationController.php?action=accept&id=" . intval($row['reservation_id']) . "' class='btn btn-sm btn-success' onclick='return confirm(\"Accepter cette réservation ?\")'><i class='fa fa-check'></i> Accepter</a> ";
                            echo "<a href='" . getControllerPath() . "reservationController.php?action=reject&id=" . intval($row['reservation_id']) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Refuser cette réservation ?\")'><i class='fa fa-times'></i> Refuser</a>";
                        } else {
                            echo "<span class='text-muted'>Traité</span>";
                        }
                        
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10' class='text-center'>Aucune réservation trouvée.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Scripts JS -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html> 
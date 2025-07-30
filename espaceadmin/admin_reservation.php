<?php
session_start();
include_once "espaceadmin/config/dbconnect.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.pHp');
    exit();
}

// Vérifier que l'utilisateur est admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: reservation.php');
    exit();
}

$message = '';

// Traitement des actions admin
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $reservation_id = intval($_GET['id']);
    
    if ($action === 'accept') {
        $update_sql = "UPDATE reservation SET status = 'Acceptée' WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param('i', $reservation_id);
        
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">Réservation acceptée avec succès !</div>';
        } else {
            $message = '<div class="alert alert-danger">Erreur lors de l\'acceptation.</div>';
        }
    } elseif ($action === 'reject') {
        $update_sql = "UPDATE reservation SET status = 'Refusée' WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param('i', $reservation_id);
        
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">Réservation refusée.</div>';
        } else {
            $message = '<div class="alert alert-danger">Erreur lors du refus.</div>';
        }
    }
}

// Récupérer toutes les réservations
$sql = "SELECT r.*, u.name as user_name, rm.room_number, rm.description as room_description, rm.price 
        FROM reservation r 
        LEFT JOIN users u ON r.user_id = u.id 
        LEFT JOIN room rm ON r.room_id = rm.room_id 
        ORDER BY r.date_reservation DESC, r.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container { 
            padding-top: 2rem; 
            padding-bottom: 2rem; 
        }
        .card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
        }
        .card-header { 
            background: linear-gradient(45deg, #667eea, #764ba2); 
            color: white; 
            border-radius: 15px 15px 0 0 !important; 
            padding: 1.5rem; 
        }
        .btn-success { 
            background: #28a745; 
            border: none; 
            border-radius: 25px; 
        }
        .btn-danger { 
            background: #dc3545; 
            border: none; 
            border-radius: 25px; 
        }
        .badge-warning { background: #ffc107; color: #000; }
        .badge-success { background: #28a745; color: white; }
        .badge-danger { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h2><i class="fa fa-calendar-check-o"></i> Gestion des Réservations</h2>
                        <p class="mb-0">Administration - <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
                    </div>
                    <div class="card-body">
                        <?= $message ?>
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Étudiant</th>
                                        <th>Chambre</th>
                                        <th>Date début</th>
                                        <th>Heures</th>
                                        <th>Durée</th>
                                        <th>Prix total</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= intval($row['id']) ?></td>
                                                <td><?= htmlspecialchars($row['user_name']) ?></td>
                                                <td>
                                                    <?= htmlspecialchars($row['room_number']) ?><br>
                                                    <small class="text-muted"><?= htmlspecialchars($row['room_description']) ?></small>
                                                </td>
                                                <td><?= htmlspecialchars($row['date_reservation']) ?></td>
                                                <td>
                                                    <?= htmlspecialchars($row['time_start']) ?> - <?= htmlspecialchars($row['time_end']) ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $nb_mois = isset($row['nb_mois']) ? intval($row['nb_mois']) : 1;
                                                    echo $nb_mois . ' mois';
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $prix_mensuel = isset($row['price']) ? floatval($row['price']) : 0;
                                                    $prix_total = $prix_mensuel * $nb_mois;
                                                    echo number_format($prix_total, 2) . ' MAD';
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
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
                                                    }
                                                    ?>
                                                    <span class="badge <?= $status_class ?>">
                                                        <?= htmlspecialchars($row['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($row['status'] == 'En attente'): ?>
                                                        <a href="?action=accept&id=<?= intval($row['id']) ?>" 
                                                           class="btn btn-success btn-sm" 
                                                           onclick="return confirm('Accepter cette réservation ?')">
                                                            <i class="fa fa-check"></i> Accepter
                                                        </a>
                                                        <a href="?action=reject&id=<?= intval($row['id']) ?>" 
                                                           class="btn btn-danger btn-sm" 
                                                           onclick="return confirm('Refuser cette réservation ?')">
                                                            <i class="fa fa-times"></i> Refuser
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">Traité</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Aucune réservation trouvée.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <a href="pei.php" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Retour
                            </a>
                            <a href="../logout.php" class="btn btn-danger">
                                <i class="fa fa-sign-out"></i> Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html> 
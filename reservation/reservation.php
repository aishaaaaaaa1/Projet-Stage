<?php
session_start();
include_once "../espaceadmin/config/dbconnect.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit();
}

// Vérifier que l'utilisateur est un étudiant (pas admin)
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: admin_reservation.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Traitement du formulaire de réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_id'])) {
    $room_id = intval($_POST['room_id']);
    $date_reservation = $_POST['date_reservation'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];
    $nb_mois = intval($_POST['nb_mois']);
    $status = 'En attente';

    // Calculer la date de fin basée sur le nombre de mois
    $date_fin = date('Y-m-d', strtotime($date_reservation . ' +' . $nb_mois . ' months'));

    // Vérifier si la chambre est déjà réservée pour cette période
    $check_sql = "SELECT * FROM reservation WHERE room_id = ? AND status != 'Refusée' AND (
        (date_reservation <= ? AND DATE_ADD(date_reservation, INTERVAL nb_mois MONTH) > ?) OR
        (date_reservation <= ? AND DATE_ADD(date_reservation, INTERVAL nb_mois MONTH) > ?) OR
        (date_reservation >= ? AND date_reservation < ?)
    )";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param('issssss', $room_id, $date_reservation, $date_reservation, $date_fin, $date_fin, $date_reservation, $date_fin);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $message = '<div class="alert alert-danger">Cette chambre est déjà réservée pour cette période.</div>';
    } else {
        $insert_sql = "INSERT INTO reservation (user_id, room_id, date_reservation, time_start, time_end, nb_mois, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param('iisssis', $user_id, $room_id, $date_reservation, $time_start, $time_end, $nb_mois, $status);
        
        if ($stmt->execute()) {
            $_SESSION['reservation_message'] = '<div class="alert alert-success">Réservation enregistrée avec succès ! Durée : ' . $nb_mois . ' mois. Elle sera traitée par l\'administration.</div>';
        } else {
            $_SESSION['reservation_message'] = '<div class="alert alert-danger">Erreur lors de la réservation.</div>';
        }

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();

    }
}

// Récupérer toutes les chambres avec leurs informations complètes
$sql = "SELECT r.*, c.category_name, c.capacity 
        FROM room r 
        LEFT JOIN room_category c ON r.category_id = c.category_id 
        ORDER BY r.room_number";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de Chambres - CampusOne</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="reservation.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h2><i class="fa fa-bed"></i> Réservation de Chambres Campus</h2>
                        <p class="mb-0">Bienvenue, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Étudiant') ?> !</p>
                        <small>Connecté en tant que : <?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></small>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_SESSION['reservation_message'])) {
                            echo $_SESSION['reservation_message'];
                            unset($_SESSION['reservation_message']);
                        }
                        ?>

                        
                        <div class="row">
                            <div class="col-md-8">
                                <h4><i class="fa fa-list"></i> Chambres Disponibles</h4>
                                <div class="row">
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while ($room = $result->fetch_assoc()): ?>
                                            <div class="col-md-6">
                                                <div class="card room-card">
                                                    <div class="position-relative">
                                                        <?php if (!empty($room['image'])): ?>
                                                            <img src="<?= htmlspecialchars($room['image']) ?>" class="room-image" alt="Chambre <?= htmlspecialchars($room['room_number']) ?>">
                                                        <?php else: ?>
                                                            <div class="room-image d-flex align-items-center justify-content-center" style="background: #f8f9fa;">
                                                                <i class="fa fa-bed" style="font-size: 3rem; color:rgb(3, 21, 102);"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <span class="status-badge <?= $room['status'] == 'Disponible' ? 'status-disponible' : 'status-occupee' ?>">
                                                            <?= htmlspecialchars($room['status']) ?>
                                                        </span>
                                                    </div>
                                                    <div class="room-info">
                                                        <h5 class="card-title">
                                                            <i class="fa fa-bed"></i> Chambre <?= htmlspecialchars($room['room_number']) ?>
                                                        </h5>
                                                        <p class="card-text">
                                                            <strong>Description :</strong><br>
                                                            <?= htmlspecialchars($room['description']) ?><br><br>
                                                            
                                                            <strong>Catégorie :</strong> <?= htmlspecialchars($room['category_name']) ?><br>
                                                            <strong>Capacité :</strong> <?= htmlspecialchars($room['capacity']) ?> personne(s)<br>
                                                            <strong>Prix :</strong> <?= htmlspecialchars($room['price']) ?> MAD/mois
                                                        </p>
                                                        
                                                        <div class="price-tag">
                                                            <i class="fa fa-money"></i> <?= htmlspecialchars($room['price']) ?> MAD/mois
                                                        </div>
                                                        
                                                        <?php if ($room['status'] == 'Disponible'): ?>
                                                            <button class="btn btn-primary btn-sm btn-block" onclick="selectRoom(<?= $room['room_id'] ?>, '<?= htmlspecialchars($room['room_number']) ?>', '<?= htmlspecialchars($room['price']) ?>')">
                                                                <i class="fa fa-calendar-plus-o"></i> Réserver cette chambre
                                                            </button>
                                                        <?php else: ?>
                                                            <button class="btn btn-secondary btn-sm btn-block" disabled>
                                                                <i class="fa fa-times"></i> Chambre occupée
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <i class="fa fa-info-circle"></i> Aucune chambre disponible pour le moment.
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="fa fa-calendar-plus-o"></i> Formulaire de Réservation</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" id="reservationForm">
                                            <input type="hidden" name="room_id" id="selected_room_id" required>
                                            
                                            <div class="form-group">
                                                <label><i class="fa fa-bed"></i> Chambre sélectionnée :</label>
                                                <input type="text" class="form-control" id="selected_room_display" readonly>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><i class="fa fa-money"></i> Prix :</label>
                                                <input type="text" class="form-control" id="selected_room_price" readonly>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><i class="fa fa-calendar"></i> Date de début de réservation :</label>
                                                <input type="date" class="form-control" name="date_reservation" required min="<?= date('Y-m-d') ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><i class="fa fa-clock-o"></i> Heure de début :</label>
                                                <input type="time" class="form-control" name="time_start" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><i class="fa fa-clock-o"></i> Heure de fin :</label>
                                                <input type="time" class="form-control" name="time_end" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><i class="fa fa-calendar-o"></i> Nombre de mois :</label>
                                                <select class="form-control" name="nb_mois" required>
                                                    <option value="">Choisir la durée</option>
                                                    <option value="1">1 mois</option>
                                                    <option value="2">2 mois</option>
                                                    <option value="3">3 mois</option>
                                                    <option value="6">6 mois</option>
                                                    <option value="12">12 mois</option>
                                                </select>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fa fa-check"></i> Confirmer la réservation
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <a href="../pei/pei.php" class="btn btn-secondary btn-block">
                                        <i class="fa fa-arrow-left"></i> Retour à mon espace
                                    </a>
                                </div>
                                
                                <div class="mt-3">
                                    <a href="../accueil/accueil.php" class="btn btn-info btn-block" style="background-color:rgb(2, 35, 68);">
                                        <i class="fa fa-home"></i> Accueil
                                    </a>
                                </div>
                                
                                <div class="mt-3">
                                    <a href="../logout.php" class="btn btn-danger btn-block">
                                        <i class="fa fa-sign-out"></i> Se déconnecter
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script>
        function selectRoom(roomId, roomNumber, price) {
            document.getElementById('selected_room_id').value = roomId;
            document.getElementById('selected_room_display').value = 'Chambre ' + roomNumber;
            document.getElementById('selected_room_price').value = price + ' MAD/mois';
        }
        
        // Validation du formulaire
        document.getElementById('reservationForm').addEventListener('submit', function(e) {
            const roomId = document.getElementById('selected_room_id').value;
            if (!roomId) {
                e.preventDefault();
                alert('Veuillez sélectionner une chambre.');
                return false;
            }
            
            const timeStart = document.querySelector('input[name="time_start"]').value;
            const timeEnd = document.querySelector('input[name="time_end"]').value;
            
            if (timeStart >= timeEnd) {
                e.preventDefault();
                alert('L\'heure de fin doit être après l\'heure de début.');
                return false;
            }
        });
    </script>
</body>
</html>

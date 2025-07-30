<?php
include_once "../config/dbconnect.php";
include_once "../config/paths.php";

if (!isset($_GET['id'])) {
    header('Location: viewAllrooms.php?error=missing_id');
    exit();
}
$id = intval($_GET['id']);
$sql = "SELECT * FROM room WHERE room_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();
if (!$room) {
    header('Location: viewAllrooms.php?error=not_found');
    exit();
}
// Récupérer les catégories
$cat_sql = "SELECT * FROM room_category";
$cat_result = $conn->query($cat_sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la chambre</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Modifier la chambre</h2>
    <div id="update-response"></div> <!-- Pour afficher les messages de succès/erreur -->
    <form id="updateRoomForm" method="POST">
        <input type="hidden" name="room_id" value="<?= $room['room_id'] ?>">
        <div class="form-group">
            <label>Numéro de chambre :</label>
            <input type="text" class="form-control" name="room_number" value="<?= htmlspecialchars($room['room_number']) ?>" required>
        </div>
        <div class="form-group">
            <label>Description :</label>
            <textarea class="form-control" name="description" rows="3" required><?= htmlspecialchars($room['description']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Prix par mois (€) :</label>
            <input type="number" step="0.01" class="form-control" name="price" value="<?= htmlspecialchars($room['price']) ?>" required>
        </div>
        <div class="form-group">
            <label>Catégorie :</label>
            <select class="form-control" name="category_id" id="category_select" required>
                <option value="">Choisir une catégorie</option>
                <?php
                while ($cat_row = $cat_result->fetch_assoc()) {
                    $selected = ($cat_row['category_id'] == $room['category_id']) ? 'selected' : '';
                    echo "<option value='" . intval($cat_row['category_id']) . "' data-capacity='" . intval($cat_row['capacity']) . "' $selected>" . htmlspecialchars($cat_row['category_name']) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Capacité :</label>
            <input type="text" class="form-control" id="capacity_display" readonly>
        </div>
        <div class="form-group">
            <label>Statut :</label>
            <select class="form-control" name="status" required>
                <option value="Disponible" <?= ($room['status'] == 'Disponible') ? 'selected' : '' ?>>Disponible</option>
                <option value="Occupée" <?= ($room['status'] == 'Occupée') ? 'selected' : '' ?>>Occupée</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="viewAllrooms.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#updateRoomForm").on('submit', function(e) {
        e.preventDefault(); // Empêche le rechargement de la page

        $.ajax({
            url: '../controller/updateRoomController.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                // Afficher le message de succès et désactiver le formulaire
                $("#update-response").html('<div class="alert alert-success">Chambre mise à jour avec succès !</div>');
                $("#updateRoomForm :input").prop("disabled", true);
            },
            error: function() {
                // Afficher le message d'erreur
                $("#update-response").html('<div class="alert alert-danger">Erreur lors de la mise à jour.</div>');
            }
        });
    });
});
</script>
<script>
    // Mettre à jour la capacité quand une catégorie est sélectionnée
    document.getElementById('category_select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const capacity = selectedOption.getAttribute('data-capacity');
        const capacityDisplay = document.getElementById('capacity_display');
        
        if (capacity) {
            capacityDisplay.value = capacity + ' personne(s)';
        } else {
            capacityDisplay.value = '';
        }
    });
    
    // Initialiser la capacité au chargement de la page
    window.addEventListener('load', function() {
        const categorySelect = document.getElementById('category_select');
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const capacity = selectedOption.getAttribute('data-capacity');
        const capacityDisplay = document.getElementById('capacity_display');
        
        if (capacity) {
            capacityDisplay.value = capacity + ' personne(s)';
        }
    });
</script>
</body>
</html> 
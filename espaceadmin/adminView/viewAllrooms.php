<?php
    include_once "../config/dbconnect.php";
    include_once "../config/paths.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Chambres</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Font Awesome + CSS -->
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
        <h2>Gestion des Chambres</h2>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">Chambre ajoutée avec succès !</div>
        <?php endif; ?>
        
        <?php if(isset($_GET['error']) && $_GET['error'] == 'room_exists'): ?>
            <div class="alert alert-danger">Erreur : Ce numéro de chambre existe déjà !</div>
        <?php endif; ?>
        
        <?php if(isset($_GET['update']) && $_GET['update'] == 'success'): ?>
            <div class="alert alert-success">Chambre mise à jour avec succès !</div>
        <?php endif; ?>
        
        <?php if(isset($_GET['update']) && $_GET['update'] == 'error'): ?>
            <div class="alert alert-danger">Erreur lors de la mise à jour de la chambre.</div>
        <?php endif; ?>
        
        <?php if(isset($_GET['warning']) && $_GET['warning'] == 'has_reservations'): ?>
            <div class="alert alert-warning">
                <strong>Attention !</strong> Cette chambre a <?php echo htmlspecialchars($_GET['count']); ?> réservation(s) associée(s). 
                La suppression de cette chambre supprimera également toutes les réservations associées.
                <br><br>
                <a href="<?php echo getControllerPath(); ?>deleteRoomController.php?id=<?php echo htmlspecialchars($_GET['room_id']); ?>&confirm=1" 
                   class="btn btn-danger btn-sm">
                    <i class="fa fa-trash"></i> Supprimer quand même
                </a>
                <a href="viewAllrooms.php" class="btn btn-secondary btn-sm">Annuler</a>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['success']) && isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php elseif(isset($_GET['success'])): ?>
            <div class="alert alert-success">Chambre supprimée avec succès !</div>
        <?php endif; ?>
        
        <!-- Bouton pour ajouter une chambre -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addRoomModal">
            <i class="fa fa-plus"></i> Ajouter une Chambre
        </button>

        <!-- Tableau des chambres -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Catégorie</th>
                    <th>Prix par mois</th>
                    <th>Capacité</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Requête pour récupérer les chambres avec leurs catégories et capacités
                $sql = "SELECT r.*, c.category_name, c.capacity 
                        FROM room r 
                        LEFT JOIN room_category c ON r.category_id = c.category_id";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        // Affichage de l'image ou icône par défaut
                        if (!empty($row["image"])) {
                            echo "<td><img src='../" . htmlspecialchars($row["image"]) . "' width='50' height='50' style='object-fit:cover;'></td>";
                        } else {
                            echo "<td><span class='fa fa-bed'></span></td>";
                        }
                        echo "<td>" . htmlspecialchars($row["room_number"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["category_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["price"]) . " €</td>";
                        echo "<td>" . htmlspecialchars($row["capacity"]) . " pers.</td>";
                        echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                        echo "<td>";
                        echo "<a href='editRoom.php?id=" . intval($row['room_id']) . "' class='btn btn-sm btn-warning'><i class='fa fa-edit'></i></a> ";
                        echo "<a href='" . getControllerPath() . "deleteRoomController.php?id=" . intval($row['room_id']) . "' class='btn btn-sm btn-danger'><i class='fa fa-trash'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>Aucune chambre trouvée.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal pour ajouter une chambre -->
    <div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?php echo getControllerPath(); ?>addRoomController.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="add_room" value="1">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoomModalLabel">Ajouter une Chambre</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nom de la chambre :</label>
                            <input type="text" class="form-control" name="room_number" required>
                        </div>
                        <div class="form-group">
                            <label>Description :</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Prix par mois (€) :</label>
                            <input type="number" step="0.01" class="form-control" name="price" required>
                        </div>
                        <div class="form-group">
                            <label>Catégorie :</label>
                            <select class="form-control" name="category_id" id="category_select" required>
                                <option value="">Choisir une catégorie</option>
                                <?php
                                $cat_sql = "SELECT * FROM room_category";
                                $cat_result = $conn->query($cat_sql);
                                while ($cat_row = $cat_result->fetch_assoc()) {
                                    echo "<option value='" . intval($cat_row['category_id']) . "' data-capacity='" . intval($cat_row['capacity']) . "'>" . htmlspecialchars($cat_row['category_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Capacité :</label>
                            <input type="text" class="form-control" id="capacity_display" readonly>
                        </div>
                        <div class="form-group">
                            <label>Image :</label>
                            <input type="file" class="form-control-file" name="room_image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" name="add_room">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts JS -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="../assets/js/script.js"></script>
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
    </script>
</body>
</html>

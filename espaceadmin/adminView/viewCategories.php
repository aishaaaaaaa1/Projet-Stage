<?php
    include_once "../config/dbconnect.php";
    include_once "../config/paths.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Catégories</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php 
        // Définir le chemin de base
        $base_path = dirname(__DIR__);
        include $base_path . "/adminHeader.php"; 
        include $base_path . "/sidebar.php"; 
    ?>

    <div class="container mt-4">
        <h2>Gestion des Catégories</h2>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">Catégorie ajoutée avec succès !</div>
        <?php endif; ?>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger">Erreur lors de l'ajout de la catégorie.</div>
        <?php endif; ?>
        
        <!-- Bouton pour ajouter une catégorie -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addCategoryModal">
            <i class="fa fa-plus"></i> Ajouter une Catégorie
        </button>

        <!-- Tableau des catégories -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom de la Catégorie</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM category";
                $result = $conn->query($sql);
                $count = 1;
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $count . "</td>";
                        echo "<td>" . $row["category_name"] . "</td>";
                        echo "<td>" . ($row["type"] ?? "Plat") . "</td>";
                        echo "<td>";
                        echo "<a href='" . getControllerPath() . "catDeleteController.php?record=" . $row['category_id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Supprimer cette catégorie ?\")'><i class='fa fa-trash'></i> Supprimer</a>";
                        echo "</td>";
                        echo "</tr>";
                        $count++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal pour ajouter une catégorie -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une Catégorie</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="<?php echo getControllerPath(); ?>addCatController.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nom de la catégorie:</label>
                            <input type="text" class="form-control" name="c_name" required>
                        </div>
                        <div class="form-group">
                            <label>Type:</label>
                            <select class="form-control" name="c_type" required>
                                <option value="">Choisir le type</option>
                                <option value="Chambre">Chambre</option>
                                <option value="Plat">Plat</option>
                                <option value="Livre">Livre</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" name="upload">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
   
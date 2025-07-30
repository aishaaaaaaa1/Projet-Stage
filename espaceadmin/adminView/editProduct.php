<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Produit</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include '../sidebar.php'; ?>
    <div id="main" style="margin-left:250px; padding:20px;">
        <div class="table-header">
            <h2>Modifier le Produit</h2>
        </div>
        <div class="table-container">
            <?php
                include_once "../config/dbconnect.php";
                $product_id = $_GET['id'];
                $sql = "SELECT * FROM produits WHERE id = $product_id";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $cat_id = $row['categorie_id'];
            ?>
            <form action="../controller/updateItemController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                <div class="form-group">
                    <label>Nom du produit:</label>
                    <input type="text" class="form-control" name="p_name" value="<?= $row['nom'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea class="form-control" name="p_desc" required><?= $row['description'] ?></textarea>
                </div>
                <div class="form-group">
                    <label>Prix:</label>
                    <input type="number" step="0.01" class="form-control" name="p_price" value="<?= $row['prix'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Catégorie:</label>
                    <select name="category" class="form-control" required>
                        <?php
                            $sql_cat = "SELECT * FROM categories";
                            $result_cat = $conn->query($sql_cat);
                            while($row_cat = $result_cat->fetch_assoc()){
                                $selected = ($row_cat['id'] == $cat_id) ? "selected" : "";
                                echo "<option value='{$row_cat['id']}' $selected>{$row_cat['nom']}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Image actuelle:</label>
                    <img src="../<?= $row['image_url'] ?>" width="150">
                </div>
                <div class="form-group">
                    <label>Nouvelle image (optionnel):</label>
                    <input type="file" class="form-control-file" name="new_image">
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
</body>
</html> 
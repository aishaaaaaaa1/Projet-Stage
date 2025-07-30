<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . "/../config/dbconnect.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Produits</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <?php 
    $base_path = dirname(__DIR__);
    include $base_path . "/adminHeader.php";
    include '../sidebar.php'; 
  ?>

  <div id="main" style="margin-left:250px; padding:20px;">
    <div class="table-header">
        <h2>Gestion des Produits</h2>
    </div>
    <div class="table-container">
      <table class="table">
        <thead>
          <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Image</th>
            <th class="text-center">Nom</th>
            <th class="text-center">Description</th>
            <th class="text-center">Catégorie</th>
            <th class="text-center">Prix</th>
            <th class="text-center" colspan="2">Action</th>
          </tr>
        </thead>
        <?php
          include_once "../config/dbconnect.php";
          $sql="SELECT p.*, c.nom as categorie_nom 
                FROM produits p 
                LEFT JOIN categories c ON p.categorie_id = c.id";
          $result=$conn-> query($sql);
          if ($result-> num_rows > 0){
            while ($row=$result-> fetch_assoc()) {
        ?>
        <tr>
          <td><?=$row["id"]?></td>
          <td><img height='100px' src='../<?=$row["image_url"]?>'></td>
          <td><?=$row["nom"]?></td>
          <td><?=$row["description"]?></td>      
          <td><?=$row["categorie_nom"]?></td> 
          <td><?=number_format($row["prix"], 2, ',', ' ')?> €</td>     
          <td><a href="editProduct.php?id=<?=$row['id']?>" class="btn btn-primary">Modifier</a></td>
          <td><button class="btn btn-danger" onclick="deleteProduct('<?=$row['id']?>')">Supprimer</button></td>
          </tr>
          <?php
              }
            }
          ?>
      </table>

      <!-- Trigger the modal with a button -->
      <button type="button" class="btn btn-secondary " style="height:40px" data-toggle="modal" data-target="#myModal">
        Ajouter Produit
      </button>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Nouveau Produit</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form  enctype='multipart/form-data' action="../controller/addItemController.php" method="POST">
            <div class="form-group">
              <label for="name">Nom du produit:</label>
              <input type="text" class="form-control" name="p_name" required>
            </div>
            <div class="form-group">
              <label for="price">Prix:</label>
              <input type="number" class="form-control" name="p_price" required>
            </div>
            <div class="form-group">
              <label for="desc">Description:</label>
              <input type="text" class="form-control" name="p_desc" required>
            </div>
            <div class="form-group">
              <label>Catégorie:</label>
              <select name="category" class="form-control">
                <option value="" disabled selected>Choisir une catégorie</option>
                <?php
                  $sql="SELECT * from categories";
                  $result=$conn-> query($sql);
                  if ($result-> num_rows > 0){
                    while ($row=$result-> fetch_assoc()) {
                      echo"<option value='".$row['id']."'>".$row['nom']."</option>";
                    }
                  }
                ?>
              </select>
            </div>
            <div class="form-group">
                <label for="file">Image:</label>
                <input type="file" class="form-control-file" name="file">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Ajouter Produit</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px">Fermer</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
  <script src="../assets/js/script.js"></script>

  <script>
    function deleteProduct(id){
        if(confirm("Êtes-vous sûr de vouloir supprimer ce produit?")){
            $.ajax({
                url:"../controller/deleteItemController.php",
                method:"post",
                data:{record:id},
                success:function(data){
                    alert(data);
                    location.reload();
                }
            });
        }
    }
  </script>

</body>
</html> 
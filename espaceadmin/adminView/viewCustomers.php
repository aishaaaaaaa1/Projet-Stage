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
  <title>Gestion des Clients</title>
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
      <h2>Gestion des Clients</h2>
    </div>
    <div class="table-container">
      <table class="table">
        <thead>
          <tr>
            <th class="text-center">S.N.</th>
            <th class="text-center">Nom</th>
            <th class="text-center">Email</th>
            <th class="text-center">Année scolaire</th>
            <th class="text-center">Filière - Niveau</th>
          </tr>
        </thead>
        <?php
          include_once "../config/dbconnect.php";
          // Afficher tous les utilisateurs (pas de colonne isAdmin dans cette table)
          $sql="SELECT * from users";
          $result=$conn-> query($sql);
          $count=1;
          if ($result-> num_rows > 0){
            while ($row=$result-> fetch_assoc()) {
        ?>
        <tr>
          <td><?=$count?></td>
          <td><?=$row["name"]?></td>
          <td><?=$row["email"]?></td>
          <td><?=$row["annee_scolaire"]?></td>
          <td><?=$row["filiere"]?> - <?=$row["niveau"]?></td>
        </tr>
        <?php
              $count=$count+1;
            }
          }
        ?>
      </table>
    </div>
  </div>
  <script src="../assets/js/script.js"></script>
</body>
</html>
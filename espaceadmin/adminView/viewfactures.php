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
  <title>Gestion des Factures</title>
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
      <h2>Gestion des Factures</h2>
    </div>
    <div class="table-container">
      <table class="table">
        <thead>
          <tr>
            <th class="text-center">S.N.</th>
            <th class="text-center">Étudiant</th>
            <th class="text-center">Description</th>
            <th class="text-center">Montant</th>
            <th class="text-center">Date Création</th>
            <th class="text-center">Date Échéance</th>
            <th class="text-center">Statut</th>
          </tr>
        </thead>
        <?php
          include_once "../config/dbconnect.php";
          // Récupérer toutes les factures avec les informations des utilisateurs
          $sql="SELECT f.*, u.name as user_name, u.email as user_email 
                FROM factures f 
                LEFT JOIN users u ON f.id_etudiant = u.id 
                ORDER BY f.created_at DESC";
          $result=$conn-> query($sql);
          $count=1;
          if ($result-> num_rows > 0){
            while ($row=$result-> fetch_assoc()) {
        ?>
        <tr>
          <td><?=$count?></td>
          <td><?=$row["user_name"]?></td>
          <td><?=$row["description"]?></td>
          <td><?=$row["montant"]?> MAD</td>
          <td><?=date('d/m/Y', strtotime($row["created_at"]))?></td>
          <td><?=date('d/m/Y', strtotime($row["date_echeance"]))?></td>
          <td>
            <?php 
            $status_class = '';
            switch($row["statut"]) {
                case 'Payée':
                    $status_class = 'text-success';
                    break;
                case 'En attente':
                    $status_class = 'text-warning';
                    break;
                case 'En retard':
                    $status_class = 'text-danger';
                    break;
                default:
                    $status_class = 'text-muted';
            }
            ?>
            <span class="<?=$status_class?> font-weight-bold">
              <?=$row["statut"]?>
            </span>
          </td>
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

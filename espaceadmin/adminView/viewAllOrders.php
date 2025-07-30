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
  <title>Gestion des Commandes</title>
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
      <h2>Gestion des Commandes</h2>
    </div>
    <div class="table-container">
      <table class="table">
        <thead>
          <tr>
            <th>O.N.</th>
            <th>Client</th>
            <th>N° Chambre</th>
            <th>Date</th>
            <th>Total</th>
            <th>Moyen de paiement</th>
            <th>Actions</th>
          </tr>
        </thead>
        <?php
          include_once "../config/dbconnect.php";
          // Jointure avec la table users pour récupérer le nom du client
          $sql="SELECT c.*, u.name as customer_name 
                FROM commandes c 
                LEFT JOIN users u ON c.user_id = u.id 
                ORDER BY c.date_commande DESC";
          $result=$conn-> query($sql);
          if ($result-> num_rows > 0){
            while ($row=$result-> fetch_assoc()) {
        ?>
        <tr>
          <td><?=$row["id"]?></td>
          <td><?=$row["customer_name"] ?? 'N/A'?></td>
          <td><?=$row["adresse_livraison"]?></td>
          <td><?=$row["date_commande"]?></td>
          <td><?=number_format($row["montant_total"], 2, ',', ' ')?> €</td>
          <td>Cash</td>
          <td>
            <button class="btn btn-primary open-modal" data-href="./getOrderDetails.php?order_id=<?= $row['id'] ?>">
              Voir Détails
            </button>
          </td>
        </tr>
        <?php
            }
          }
        ?>
      </table>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="viewModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header"></div>
        <div class="order-view-modal modal-body"></div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function(){
      $('.open-modal').on('click',function(){
        var dataURL = $(this).attr('data-href');
        $('.order-view-modal').load(dataURL,function(){
          $('#viewModal').modal({show:true});
        });
      });
    });
  </script>
  <script src="../assets/js/script.js"></script>
</body>
</html>
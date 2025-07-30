<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
  
<?php
    include "./adminHeader.php";
    include "./sidebar.php";
    include_once "./config/dbconnect.php";
?>

<div id="main-content" class="container allContent-section py-4">
  <div class="row">

    <!-- Total Customers -->
    <div class="col-sm-3">
      <div class="card text-center bg-dark text-white p-3">
        <i class="fa fa-users mb-2" style="font-size: 60px;"></i>
        <h4>Total Customers</h4>
        <h5>
          <?php
            $sql = "SELECT COUNT(*) AS total FROM users"; // ou WHERE is_admin=0 si la colonne existe
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            echo $row['total'];
          ?>
        </h5>
      </div>
    </div>

    <!-- Total Factures -->
    <div class="col-sm-3">
      <div class="card text-center bg-dark text-white p-3">
        <i class="fa fa-file-text mb-2" style="font-size: 60px;"></i>
        <h4>Total Factures</h4>
        <h5>
          <?php
        $sql = "SELECT COUNT(*) AS total FROM factures";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo $row['total'];
      ?>
        </h5>
      </div>
    </div>

    <!-- Total Reservations -->
    <div class="col-sm-3">
      <div class="card text-center bg-dark text-white p-3">
        <i class="fa fa-calendar-check-o mb-2" style="font-size: 60px;"></i>
        <h4>Total Reservations</h4>
        <h5>
          <?php
            $sql = "SELECT COUNT(*) AS total FROM reservation";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            echo $row['total'];
          ?>
        </h5>
      </div>
    </div>

    <!-- Total Chambres -->
    <div class="col-sm-3">
      <div class="card text-center bg-dark text-white p-3">
        <i class="fa fa-bed mb-2" style="font-size: 60px;"></i>
        <h4>Total Chambres</h4>
        <h5>
          <?php
            $sql = "SELECT COUNT(*) AS total FROM room";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            echo $row['total'];
          ?>
        </h5>
      </div>
    </div>

  </div>
  
  <!-- Statistiques de paiement -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5>📊 Statistiques des Paiements</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
              <div class="text-center">
                <h4 class="text-success"><?php 
                  $sql_payees = "SELECT COUNT(*) as count FROM factures WHERE statut = 'Payée'";
                  $result_payees = $conn->query($sql_payees);
                  $count_payees = $result_payees->fetch_assoc()['count'];
                  echo $count_payees;
                ?></h4>
                <p class="text-muted">Factures Payées</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="text-center">
                <h4 class="text-warning"><?php 
                  $sql_attente = "SELECT COUNT(*) as count FROM factures WHERE statut = 'En attente'";
                  $result_attente = $conn->query($sql_attente);
                  $count_attente = $result_attente->fetch_assoc()['count'];
                  echo $count_attente;
                ?></h4>
                <p class="text-muted">En Attente</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="text-center">
                <h4 class="text-danger"><?php 
                  $sql_retard = "SELECT COUNT(*) as count FROM factures WHERE statut = 'En retard'";
                  $result_retard = $conn->query($sql_retard);
                  $count_retard = $result_retard->fetch_assoc()['count'];
                  echo $count_retard;
                ?></h4>
                <p class="text-muted">En Retard</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="text-center">
                <h4 class="text-info"><?php 
                  $sql_total = "SELECT SUM(montant) as total FROM factures WHERE statut = 'Payée'";
                  $result_total = $conn->query($sql_total);
                  $total_paye = $result_total->fetch_assoc()['total'] ?? 0;
                  echo $total_paye . ' MAD';
                ?></h4>
                <p class="text-muted">Total Payé</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php
  if (isset($_GET['category']) && $_GET['category'] == "success") {
      echo '<script>alert("Category Successfully Added")</script>';
  } else if (isset($_GET['category']) && $_GET['category'] == "error") {
      echo '<script>alert("Adding Unsuccess")</script>';
  }
?>

<!-- JS -->
<script src="./assets/js/ajaxWork.js"></script>
<script src="./assets/js/script.js"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

</body>
</html>

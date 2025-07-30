<?php include_once __DIR__ . "/config/paths.php"; ?>

<!-- Sidebar -->
<div class="sidebar" id="mySidebar">
  <div class="side-header text-center">
    <a href="<?php echo getBasePath(); ?>index.php">
      <img src="<?php echo getBasePath(); ?>assets/images/logo.png" width="120" height="120" alt="Swiss Collection">
    </a>
    <h5 style="margin-top:10px;">Hello, Admin</h5>
  </div>

  <hr style="border:1px solid; background-color:#8a7b6d; border-color:#3B3131;">
  
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>

  <!-- Dashboard -->
  <a href="<?php echo getBasePath(); ?>index.php">
    <i class="fa fa-home"></i> Dashboard
  </a>

  <!-- Customers -->
  <a href="<?php echo getAdminViewPath(); ?>viewCustomers.php">
    <i class="fa fa-users"></i> Customers
  </a>

  <!-- Chambres (ex-Products) -->
  <a href="<?php echo getAdminViewPath(); ?>viewAllRooms.php">
    <i class="fa fa-bed"></i> Chambres
  </a>

  <!-- Produits -->
  <a href="<?php echo getAdminViewPath(); ?>viewAllProducts.php">
    <i class="fa fa-cutlery"></i> Produits
  </a>

  <!-- Commandes -->
  <a href="<?php echo getAdminViewPath(); ?>viewAllOrders.php">
    <i class="fa fa-shopping-cart"></i> Commandes
  </a>

  <!-- Réservations -->
  <a href="<?php echo getAdminViewPath(); ?>viewReservations.php">
    <i class="fa fa-calendar-check-o"></i> Reservations
  </a>

  <!-- Factures -->
  <a href="<?php echo getAdminViewPath(); ?>viewFactures.php">
    <i class="fa fa-file-text"></i> Factures
  </a>
</div>

<!-- Main toggle button -->
<div id="main">
  <button class="openbtn" onclick="openNav()"><i class="fa fa-home"></i></button>
</div>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . "/config/dbconnect.php";
include_once __DIR__ . "/config/paths.php";
?>
       
<!-- nav -->
<nav class="navbar navbar-expand-lg navbar-light px-5" style="background-color: #3B3131;">
   
        <img src="/stagepro/espaceadmin/assets/images/logo.png" width="80" height="80" alt="Swiss Collection">
    
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0"></ul>
    
    <div class="user-cart">  
        <?php           
        if(isset($_SESSION['user_id'])){
          ?>
          <a href="<?php echo getBasePath(); ?>../logout.php" style="text-decoration:none;" title="Déconnexion">
            <i class="fa fa-sign-out mr-5" style="font-size:30px; color:#fff;" aria-hidden="true"></i>
          </a>
          <?php
        } else {
            ?>
            <a href="/stagepro/login/login.php" style="text-decoration:none;">
                <i class="fa fa-sign-in mr-5" style="font-size:30px; color:#fff;" aria-hidden="true"></i>
            </a>
            <?php
        } ?>
    </div>
</nav>
</nav>

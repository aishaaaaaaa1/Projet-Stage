<?php
session_start();
session_destroy();
header("Location: ../accueil/accueil.php?message=logged_out");
exit();
?>

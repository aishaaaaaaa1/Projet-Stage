<?php
session_start();

// Si l'utilisateur est connecté, rediriger vers la page de réservation
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    header('Location: reservation_chambres.php');
    exit();
}

// Sinon, rediriger vers la page de connexion
header('Location: login.pHp');
exit();
?> 
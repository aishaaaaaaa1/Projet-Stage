<?php
require_once 'includes/session.php';
require_once 'includes/fonctions.php';

if (isset($_POST['produit_id'])) {
    retirer_du_panier((int)$_POST['produit_id']);
}
header('Location: restau.php');
exit; 
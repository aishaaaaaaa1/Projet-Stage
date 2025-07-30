<?php
require_once 'includes/session.php';
require_once 'includes/fonctions.php';

header('Content-Type: application/json');

$response = ['success' => false, 'panier' => []];

if (isset($_POST['produit_id']) && isset($_POST['quantite'])) {
    $produit_id = (int)$_POST['produit_id'];
    $quantite_change = (int)$_POST['quantite'];

    $panier_actuel = get_panier();
    $quantite_actuelle = isset($panier_actuel[$produit_id]) ? $panier_actuel[$produit_id] : 0;

    $nouvelle_quantite = $quantite_actuelle + $quantite_change;

    if ($nouvelle_quantite >= 1) {
        $_SESSION['panier'][$produit_id] = $nouvelle_quantite;
    } else {
        unset($_SESSION['panier'][$produit_id]);
    }

    $response['success'] = true;
    $response['panier'] = $_SESSION['panier'];
}

echo json_encode($response);
exit;

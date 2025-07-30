<?php
require_once 'config.php';

// Fonctions panier
function ajouter_au_panier($produit_id, $quantite = 1) {
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
    if (isset($_SESSION['panier'][$produit_id])) {
        $_SESSION['panier'][$produit_id] += $quantite;
    } else {
        $_SESSION['panier'][$produit_id] = $quantite;
    }
}

function retirer_du_panier($produit_id) {
    if (isset($_SESSION['panier'][$produit_id])) {
        unset($_SESSION['panier'][$produit_id]);
    }
}

function vider_panier() {
    unset($_SESSION['panier']);
}

function get_panier() {
    return isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
}


// Fonctions produits
function get_produit($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_produits($ids) {
    global $pdo;
    if (empty($ids)) return [];
    $in  = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id IN ($in)");
    $stmt->execute($ids);
    return $stmt->fetchAll();
}

function get_numero_chambre_utilisateur($user_id) {
    global $pdo;

    // On cherche la réservation la plus récente de l'utilisateur pour trouver sa chambre.
 
    $query = "SELECT r.room_number 
              FROM room r
              JOIN reservation res ON r.room_id = res.room_id
              WHERE res.user_id = ?
              ORDER BY res.created_at DESC -- On prend la réservation la plus récente
              LIMIT 1";
              
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id]);
    $result = $stmt->fetch();
    
    // S'il a une chambre, on retourne le numéro de la chambre, sinon 'N/A'
    return $result ? $result['room_number'] : 'N/A';
}

function get_user_by_id($user_id) {
    global $pdo;
    if (!$user_id) {
        return null;
    }
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

function get_utilisateur_connecte() {
    // À adapter selon ton système d'authentification
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    }
    return null;
} 
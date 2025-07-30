<?php
require_once 'includes/session.php';
require_once 'includes/config.php';
require_once 'includes/fonctions.php';

$panier = get_panier();
if (empty($panier)) {
    header('Location: restau.php?erreur=panier_vide');
    exit;
}

// Récupérer le numéro de chambre et l'ID de l'étudiant
$user_id = get_utilisateur_connecte();
$numero_chambre = 'N/A'; // Valeur par défaut
if ($user_id) {
    $numero_chambre = get_numero_chambre_utilisateur($user_id);
}

// 1. Créer la commande avec le user_id
$stmt = $pdo->prepare("INSERT INTO commandes (user_id, date_commande, type, adresse_livraison, montant_total) VALUES (?, NOW(), 'en ligne', ?, 0)");
$stmt->execute([$user_id, $numero_chambre]);
$commande_id = $pdo->lastInsertId();

// 2. Ajouter les lignes de commande
$total = 0;
foreach ($panier as $produit_id => $quantite) {
    $produit = get_produit($produit_id);
    if (!$produit) continue;
    $prix = $produit['prix'];
    $sous_total = $prix * $quantite;
    $total += $sous_total;
    $stmt = $pdo->prepare("INSERT INTO lignes_commande (commande_id, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
    $stmt->execute([$commande_id, $produit_id, $quantite, $prix]);
}

// 3. Mettre à jour le montant total
$stmt = $pdo->prepare("UPDATE commandes SET montant_total = ? WHERE id = ?");
$stmt->execute([$total, $commande_id]);

// 4. Vider le panier
vider_panier();

// Rediriger vers la page principale avec un message de confirmation
header('Location: restau.php?commande_success=1&commande_id=' . $commande_id);
exit; 
<?php
session_start();
$host = 'localhost';
$db   = 'nom_de_ta_base'; // Remplace par le nom de ta base
$user = 'utilisateur';   // Remplace par ton utilisateur
$pass = 'motdepasse';    // Remplace par ton mot de passe
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_POST['produit_id']) && isset($_POST['quantite'])) {
    $produit_id = (int)$_POST['produit_id'];
    $quantite = (int)$_POST['quantite'];
    if ($quantite < 1) $quantite = 1;

    // 1. Créer une commande si aucune n'est en cours (exemple simple, à adapter selon ton système utilisateur)
    if (!isset($_SESSION['commande_id'])) {
        $stmt = $pdo->prepare("INSERT INTO commandes (date_commande, statut, type, montant_total) VALUES (NOW(), 'en cours', 'en ligne', 0)");
        $stmt->execute();
        $_SESSION['commande_id'] = $pdo->lastInsertId();
    }
    $commande_id = $_SESSION['commande_id'];

    // 2. Récupérer le prix du produit
    $stmt = $pdo->prepare("SELECT prix FROM produits WHERE id = ?");
    $stmt->execute([$produit_id]);
    $produit = $stmt->fetch();
    if (!$produit) {
        die("Produit introuvable.");
    }
    $prix_unitaire = $produit['prix'];

    // 3. Ajouter à lignes_commande (ou mettre à jour la quantité si déjà présent)
    $stmt = $pdo->prepare("SELECT id, quantite FROM lignes_commande WHERE commande_id = ? AND produit_id = ?");
    $stmt->execute([$commande_id, $produit_id]);
    $ligne = $stmt->fetch();
    if ($ligne) {
        // Mise à jour de la quantité
        $stmt = $pdo->prepare("UPDATE lignes_commande SET quantite = quantite + ? WHERE id = ?");
        $stmt->execute([$quantite, $ligne['id']]);
    } else {
        // Insertion
        $stmt = $pdo->prepare("INSERT INTO lignes_commande (commande_id, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
        $stmt->execute([$commande_id, $produit_id, $quantite, $prix_unitaire]);
    }

    // 4. Mettre à jour le montant total de la commande
    $stmt = $pdo->prepare("UPDATE commandes SET montant_total = (SELECT SUM(quantite * prix_unitaire) FROM lignes_commande WHERE commande_id = ?) WHERE id = ?");
    $stmt->execute([$commande_id, $commande_id]);

    // 5. Rediriger ou afficher un message
    header("Location: restau.html?success=1");
    exit;
} else {
    die("Données manquantes.");
} 
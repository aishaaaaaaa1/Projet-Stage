<?php
session_start();

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=users", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}

// Vérifie la connexion de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    die("Vous devez être connecté pour effectuer un paiement.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Méthode non autorisée.");
}

try {
    // Vérifie s'il y a des factures à payer
    $stmt = $pdo->prepare("SELECT montant FROM factures WHERE id_etudiant = ? AND statut IN ('En attente', 'En retard')");
    $stmt->execute([$user_id]);
    $factures = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($factures)) {
        die("Aucune facture à payer.");
    }

    // Met à jour les statuts
    $update = $pdo->prepare("UPDATE factures SET statut = 'Payée', updated_at = NOW() WHERE id_etudiant = ? AND statut IN ('En attente', 'En retard')");
    $update->execute([$user_id]);

    // Affiche le résultat
    $total = array_sum($factures);
    $count = count($factures);
    echo "Paiement effectué avec succès.<br>";
    echo "Nombre de factures payées : $count<br>";
    echo "Montant total payé : $total DH";

} catch (Exception $e) {
    echo "Une erreur est survenue pendant le paiement.";
}
?>

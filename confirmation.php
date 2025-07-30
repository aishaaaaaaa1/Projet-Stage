<?php
if (!isset($_GET['commande'])) {
    header('Location: menu.php');
    exit;
}
$commande_id = (int)$_GET['commande'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Confirmation</title>
</head>
<body>
<h1>Merci pour votre commande !</h1>
<p>Votre numéro de commande est : <?= $commande_id ?></p>
<a href="menu.php">Retour au menu</a>
</body>
</html> 
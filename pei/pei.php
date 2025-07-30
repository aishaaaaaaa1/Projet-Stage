<?php
// === Connexion DB: db.php ===
$host = 'localhost';
$db = 'users'; // Utilisation de ta base existante "users"
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur DB: " . $e->getMessage());
}

session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Portail Étudiant - CampusOne</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 2rem; background: #f9f9f9; text-align: center; }
    .section { background: #fff; padding: 1rem; margin: 2rem auto; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 600px; text-align: left; }
    input, select, button { margin: 0.5rem 0; padding: 0.5rem; width: 100%; max-width: 400px; }
    h2 { color: #2c3e50; }
    .welcome { color: #2c3e50; font-size: 24px; margin-bottom: 20px; }
    .login-prompt { color: #a68868; font-size: 20px; margin-bottom: 15px; }
    .login-link { display: inline-block; padding: 10px 15px; background: #071739; color: white; text-decoration: none; border-radius: 5px; }
    .login-link:hover { background: #071739; }
    .top-link { position: absolute; top: 20px; left: 30px; }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <a href="../accueil/accueil.php" class="login-link top-link">
    <i class="fa fa-home"></i> Accueil
  </a>

<?php if (isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])):
    $id_etudiant = $_SESSION['user_id'] ?? 1;
    // Sélection des infos utilisateur (table users)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? ");
    $stmt->execute([$id_etudiant]);
    $user_data = $stmt->fetch();


?>

<div class="welcome">
  Bienvenue, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Étudiant') ?> !
</div>


<!-- Espace personnel -->
<div class="section">
  <h2>Espace Personnel</h2>
  <?php if ($user_data): ?>
    <p><strong>Nom :</strong> <?= htmlspecialchars($user_data['name']) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($user_data['email']) ?></p>
    <p><strong>Année scolaire :</strong> <?= htmlspecialchars($user_data['annee_scolaire'] ?? 'Non renseigné') ?></p>
    <p><strong>Filière :</strong> <?= htmlspecialchars($user_data['filiere'] ?? 'Non renseigné') ?></p>
    <p><strong>Niveau :</strong> <?= htmlspecialchars($user_data['niveau'] ?? 'Non renseigné') ?></p>
  <?php else: ?>
    <p>Utilisateur non trouvé.</p>
  <?php endif; ?>
  
</div>



<!-- Factures -->
<div class="section">
  <h2>🏠 Factures de Chambres</h2>
  <?php
  $stmt = $pdo->prepare("SELECT * FROM factures WHERE id_etudiant = ? ORDER BY created_at DESC");
  $stmt->execute([$id_etudiant]);
  $factures = $stmt->fetchAll();
  ?>
  <?php if (count($factures) === 0): ?>
      <p>Aucune facture de chambre enregistrée.</p>
  <?php else: ?>
      <table border="1" cellpadding="5" cellspacing="0" style="width:100%; max-width:600px;">
          <thead>
              <tr>
                  <th>Description</th>
                  <th>Montant</th>
                  <th>Date</th>
                  <th>Statut</th>
                  <th>Payer</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($factures as $facture): ?>
                  <tr>
                      <td><?= htmlspecialchars($facture['description']) ?></td>
                      <td><?= $facture['montant'] ?> MAD</td>
                      <td><?= htmlspecialchars($facture['created_at']) ?></td>
                      <td>
                          <?php 
                          $status_class = '';
                          switch($facture['statut']) {
                              case 'En attente':
                                  $status_class = 'color: orange;';
                                  break;
                              case 'Payée':
                                  $status_class = 'color: green;';
                                  break;
                              case 'En retard':
                                  $status_class = 'color: red;';
                                  break;
                              default:
                                  $status_class = 'color: gray;';
                          }
                          ?>
                          <span style="<?= $status_class ?> font-weight: bold;">
                              <?= htmlspecialchars($facture['statut']) ?>
                          </span>
                      </td>
                      <td>
                        <?php if ($facture['statut'] !== 'Payée'): ?>
                          <form method="POST" action="">
                            <input type="hidden" name="facture_id" value="<?= $facture['id'] ?>">
                            <button type="submit" name="payer_facture" style="background:#a68868;color:white;border:none;padding:5px 10px;border-radius:5px;">Payer</button>
                          </form>
                        <?php else: ?>
                          <span style="color:green;">Déjà payée</span>
                        <?php endif; ?>
                      </td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
      </table>
  <?php endif; ?>

  <?php
  // Traitement du paiement (simulation)
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payer_facture'])) {
      $facture_id = intval($_POST['facture_id']);
      $stmt = $pdo->prepare("UPDATE factures SET statut = 'Payée' WHERE id = ?");
      $stmt->execute([$facture_id]);
      echo "<p style='color:green;'>Facture payée avec succès !</p>";
      // Recharge la page pour afficher le nouveau statut
      echo "<script>window.location.reload();</script>";
  }
  ?>
</div>

<!-- Réservations -->
<div class="section">
  <h2>📅 Mes Réservations</h2>
  <?php
  $stmt = $pdo->prepare("SELECT r.*, rm.room_number, rm.description as room_description, rm.price 
                         FROM reservation r 
                         LEFT JOIN room rm ON r.room_id = rm.room_id 
                         WHERE r.user_id = ? 
                         ORDER BY r.created_at DESC");
  $stmt->execute([$id_etudiant]);
  $reservations = $stmt->fetchAll();
  ?>
  <?php if (count($reservations) === 0): ?>
      <p>Aucune réservation enregistrée.</p>
  <?php else: ?>
      <table border="1" cellpadding="5" cellspacing="0" style="width:100%; max-width:800px;">
          <thead>
              <tr>
                  <th>Chambre</th>
                  <th>Date début</th>
                  <th>Heure début</th>
                  <th>Heure fin</th>
                  <th>Durée (mois)</th>
                  <th>Prix total</th>
                  <th>Statut</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($reservations as $reservation): ?>
                  <tr>
                      <td><?= htmlspecialchars($reservation['room_number']) ?> - <?= htmlspecialchars($reservation['room_description']) ?></td>
                      <td><?= htmlspecialchars($reservation['date_reservation']) ?></td>
                      <td><?= htmlspecialchars($reservation['time_start']) ?></td>
                      <td><?= htmlspecialchars($reservation['time_end']) ?></td>
                      <td><?= htmlspecialchars($reservation['nb_mois'] ?? 1) ?> mois</td>
                      <td><?= ($reservation['price'] ?? 0) * ($reservation['nb_mois'] ?? 1) ?> MAD</td>
                      <td>
                          <?php 
                          $status_class = '';
                          switch($reservation['status']) {
                              case 'En attente':
                                  $status_class = 'color: orange;';
                                  break;
                              case 'Acceptée':
                                  $status_class = 'color: green;';
                                  break;
                              case 'Refusée':
                                  $status_class = 'color: red;';
                                  break;
                              default:
                                  $status_class = 'color: gray;';
                          }
                          ?>
                          <span style="<?= $status_class ?> font-weight: bold;">
                              <?= htmlspecialchars($reservation['status']) ?>
                          </span>
                      </td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
      </table>
  <?php endif; ?>
  
  <p style="margin-top: 1rem;">
    <a href="../reservation/reservation.php" class="login-link">📋 Faire une nouvelle réservation</a>
  </p>
</div>

<!-- Déconnexion -->
<div class="section">
  <h2>Déconnexion</h2>
  <p>Pour quitter votre espace, cliquez ci-dessous :</p>
  <a href="logout.pHp" class="login-link">Se déconnecter</a>
</div>

<?php else: ?>
  <div class="login-prompt">
    Vous n'êtes pas encore connecté.
  </div>
  <a href="../login/login.php" class="login-link">Se connecter</a>
<?php endif; ?>

</body>
</html>

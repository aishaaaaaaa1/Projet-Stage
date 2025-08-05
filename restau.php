<?php
require_once 'includes/session.php';
require_once 'includes/config.php';

require_once 'includes/fonctions.php';

// Logique pour nettoyer le panier des produits qui n'existent plus
$panier_actuel = get_panier();
if (!empty($panier_actuel)) {
    // Récupère les détails des produits qui existent encore dans la BDD
    $produits_valides = get_produits(array_keys($panier_actuel));

    $panier_propre = [];
    // Reconstruit le panier avec seulement les produits valides
    foreach ($produits_valides as $produit) {
        $id_produit = $produit['id'];
        // On conserve la quantité du panier original
        $panier_propre[$id_produit] = $panier_actuel[$id_produit];
    }
    
    // Remplace l'ancien panier dans la session par le panier nettoyé
    $_SESSION['panier'] = $panier_propre;
}

// Récupérer les informations de l'utilisateur connecté
$user_id = get_utilisateur_connecte();
$user_info = null;
if ($user_id) {
    $user_info = get_user_by_id($user_id);
}

// --- VÉRIFICATION DE LA CONNEXION DE L'UTILISATEUR ---
// ... (code de vérification de session) ...

// Calculer le nombre total de produits dans le panier
$panier = get_panier();
$nombre_total_produits = 0;
foreach ($panier as $quantite) {
    $nombre_total_produits += $quantite;
}

// On récupère tous les produits, bien ordonnés par catégorie
$stmt_produits_all = $pdo->query("SELECT * FROM produits ORDER BY categorie_id, id");
$all_produits = $stmt_produits_all->fetchAll();

// On crée une nouvelle liste "vitrine" avec seulement le premier produit de chaque catégorie
$produits_showcase = [];
$displayed_categories_ids = [];
foreach ($all_produits as $produit) {
    if (!in_array($produit['categorie_id'], $displayed_categories_ids)) {
        $produits_showcase[] = $produit;
        $displayed_categories_ids[] = $produit['categorie_id'];
    }
}

// Récupérer les catégories pour les filtres
$stmt_categories = $pdo->query("SELECT * FROM categories");
$categories = $stmt_categories->fetchAll();
?>
<?php


if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, on affiche un message HTML
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Accès Restreint - CampusOne</title>
        <link rel="stylesheet" href="restau.css">
    </head>
    <body style="text-align: center; padding-top: 50px;">
        <div class="container" style="text-align: center;">
            <h2 class="h2 section-title">Accès non autorisé</h2>
            <p style="font-size: 1.2em; margin: 20px 0;">Vous devez vous connecter pour accéder au restaurant.</p>
            <a href="login/login.php" class="btn btn-primary" style="display: inline-flex;">Se connecter</a>
        </div>
    </body>
    </html>
    <?php
    exit(); // On arrête l'exécution du reste de la page
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusOne</title>

  
  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="restau.css">


  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
    rel="stylesheet">

  <!-- 
    - preload banner
  -->
  <link rel="preload" href="./assets/images/hero-banner.png" as="image">

</head>

<body id="top">

  <!-- 
    - #HEADER
  -->

  <header class="header" data-header>
    <div class="container">

      <div class="overlay" data-overlay></div>

      <a href="accueil/accueil.php" class="logo">
        <span style="font-size: 24px; font-weight: bold; color: #333;">CampusOne</span>
      </a>

      <?php if ($user_info): ?>
        <div class="user-welcome">
          Bonjour, <?= htmlspecialchars($user_info['name']) ?>
        </div>
      <?php endif; ?>

        <ul class="nav-action-list">

          <li>
            <button class="nav-action-btn" id="cart-btn" onclick="openCartModal()">
              <ion-icon name="bag-outline" aria-hidden="true"></ion-icon>

            <span class="nav-action-text">Panier</span>

            <data class="nav-action-badge" value="<?= $nombre_total_produits ?>" aria-hidden="true"><?= $nombre_total_produits ?></data>
            </button>
          </li>

        </ul>

    </div>
  </header>

  <div id="cartModal" class="cart-modal-overlay" style="display: none;">
<div class="cart-modal">
  <h2 class="cart-modal-title">Votre Panier</h2>
  <div id="cartItemsContainer">
    <?php
    $panier = get_panier();
    ?>
    <?php if (empty($panier)): ?>
      <div class="empty-cart-message">
        <ion-icon name="lock-closed-outline"></ion-icon>
        <h3>Votre panier est vide</h3>
        <p>Ajoutez des produits pour commencer vos achats</p>
      </div>
    <?php else: ?>
      <?php
      $produits = get_produits(array_keys($panier));
      $total = 0;
      ?>
      <div class="cart-items-container">
        <?php foreach ($produits as $produit): 
          $qte = $panier[$produit['id']];
          $sous_total = $produit['prix'] * $qte;
          $total += $sous_total;
        ?>
          <div class="cart-item-modal">
            <img src="<?= htmlspecialchars($produit['image_url']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" class="cart-item-image-modal">
            <div class="cart-item-info-modal">
              <h4 class="cart-item-title-modal"><?= htmlspecialchars($produit['nom']) ?></h4>
              <p class="cart-item-price-modal"><?= number_format($produit['prix'], 2, ',', ' ') ?> €</p>
            </div>
            <div class="cart-item-actions-modal">
              <button class="cart-btn-quantity" onclick="changeQuantity(<?= $produit['id'] ?>, -1)">-</button>
              <span class="cart-quantity-display" data-id="<?= $produit['id'] ?>"><?= $qte ?></span>
              <button class="cart-btn-quantity" onclick="changeQuantity(<?= $produit['id'] ?>, 1)">+</button>
              <button class="cart-btn-remove" onclick="removeFromCart(<?= $produit['id'] ?>)">
                <ion-icon name="trash-outline"></ion-icon>
                Retirer
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="cart-total-section">
        <h3 class="cart-total">Total : <?= number_format($total, 2, ',', ' ') ?> €</h3>
      </div>
      <div class="cart-modal-actions">
        <button class="cart-btn-close" onclick="closeCartModal()">Fermer</button>
        <form method="POST" action="commander.php" style="display: inline;">
          <button type="submit" class="cart-btn-checkout">Commander</button>
        </form>
       
      </div>
    <?php endif; ?>
  </div>
</div>
</div> 

  <main>
    <article>

      <!-- 
        - #HERO
      -->

      <section class="section hero" style="background-image: url('pexels-viliamphotography-29240222.jpg'); text-align: left;">
    <div class="container" style="margin-left: 0; padding-left: 50px;">

        <h2 class="h1 hero-title" style="text-align: left;">
           <strong><br>Restaurant  CampusOne</strong>
        </h2>

        <p class="hero-text" style="color: rgb(252, 245, 245); font-size: 1.4em; max-width: 50%; text-align: left;">
            Découvrez notre restaurant de campus offrant une expérience culinaire exceptionnelle. 
            Profitez de nos plats frais et délicieux dans notre restaurant ou commandez en ligne 
            pour une livraison rapide directement à votre chambre. Nos chefs préparent chaque plat 
            avec des ingrédients locaux et de saison.
        </p>

        <div style="display: flex; gap: 30px; flex-wrap: wrap; margin-top: 30px; justify-content: flex-start;">
            <button class="btn btn-primary">
                <span>Manger au Restaurant</span>
                <ion-icon name="restaurant-outline" aria-hidden="true"></ion-icon>
            </button>

            <button class="btn btn-secondary">
                <span>Commander en Ligne</span>
                <ion-icon name="bicycle-outline" aria-hidden="true"></ion-icon>
            </button>
        </div>
    </div>
</section>

 <!-- 
 




      <!-- 
        - #PRODUCT (VITRINE)
      -->
      <section class="section product" id="menu">
        <div class="container">
          <h2 class="h2 section-title">Notre Menu</h2>

          <ul class="filter-list">

            <li>
              <a href="#menu" class="filter-btn active">Tout</a>
            </li>

            <?php foreach ($categories as $categorie): ?>
            <li>
              <a href="#<?= strtolower($categorie['nom']) ?>" class="filter-btn"><?= htmlspecialchars($categorie['nom']) ?></a>
            </li>
            <?php endforeach; ?>

          </ul>

          <ul class="product-list">
            <?php foreach ($produits_showcase as $produit): ?>
              <li class="product-item">
                <div class="product-card" tabindex="0">
                  <figure class="card-banner">
                    <img src="<?= htmlspecialchars($produit['image_url']) ?>" width="312" height="350" loading="lazy"
                      alt="<?= htmlspecialchars($produit['nom']) ?>" class="image-contain">
                    <ul class="card-action-list">
                      <li class="card-action-item">
                        <button class="card-action-btn" onclick="addToCart(<?= $produit['id'] ?>)">
                          <ion-icon name="restaurant-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip">Commander</div>
                      </li>
                    </ul>
                  </figure>
                  <div class="card-content">
                    <div class="card-cat">
                      <a href="#" class="card-cat-link"><?= htmlspecialchars($produit['description']) ?></a>
                    </div>
                    <h3 class="h3 card-title">
                      <a href="#"><?= htmlspecialchars($produit['nom']) ?></a>
                    </h3>
                    <data class="card-price" value="<?= $produit['prix'] ?>">
                      <?= number_format($produit['prix'], 2, ',', ' ') ?> €
                    </data>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>

        </div>
      </section>

      <!-- Sections dynamiques par catégorie -->
      <?php foreach ($categories as $categorie): ?>
      <section class="section product" id="<?= strtolower($categorie['nom']) ?>">
        <div class="container">
          <h2 class="h2 section-title">Nos <?= htmlspecialchars($categorie['nom']) ?></h2>
          <ul class="product-list">
            <?php 
            // Filtrer les produits par catégorie
            $stmt_produits_cat = $pdo->prepare("SELECT * FROM produits WHERE categorie_id = ?");
            $stmt_produits_cat->execute([$categorie['id']]);
            $produits_categorie = $stmt_produits_cat->fetchAll();
            ?>
            <?php foreach ($produits_categorie as $produit): ?>
            <li class="product-item">
              <div class="product-card" tabindex="0">
                <figure class="card-banner">
                  <img src="<?= htmlspecialchars($produit['image_url']) ?>" width="312" height="350" loading="lazy"
                    alt="<?= htmlspecialchars($produit['nom']) ?>" class="image-contain">
                  <ul class="card-action-list">
                    <li class="card-action-item">
                      <button class="card-action-btn" onclick="addToCart(<?= $produit['id'] ?>)">
                        <ion-icon name="restaurant-outline"></ion-icon>
                      </button>
                      <div class="card-action-tooltip" id="card-label-<?= $produit['id'] ?>">Commander</div>
                    </li>
                  </ul>
                </figure>
                <div class="card-content">
                  <div class="card-cat">
                    <a href="#" class="card-cat-link"><?= htmlspecialchars($categorie['nom']) ?></a> /
                    <a href="#" class="card-cat-link"><?= htmlspecialchars($categorie['sous_categorie']) ?></a>
                  </div>
                  <h3 class="h3 card-title">
                    <a href="#"><?= htmlspecialchars($produit['nom']) ?></a>
                  </h3>
                  <data class="card-price" value="<?= $produit['prix'] ?>">
                    <?= number_format($produit['prix'], 2, ',', ' ') ?> €
                  </data>
                </div>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </section>
      <?php endforeach; ?>

     




  <!-- 
    - #FOOTER
  -->

  <footer class="footer">

    

      

        <div class="footer-link-box">

          <ul class="footer-list">

            <li>
              <p class="footer-list-title">Contactez-nous</p>
            </li>

            <li>
              <address class="footer-link">
                <ion-icon name="location"></ion-icon>

                <span class="footer-link-text">
                 CampusOne - Siège administratif,
123 Rue de l’Université,
Casablanca, Maroc
                </span>
              </address>
            </li>

            <li>
              <a href="tel:+33241420000" class="footer-link">
                <ion-icon name="call"></ion-icon>

                <span class="footer-link-text">+212 5 22 123 456</span>
              </a>
            </li>

            <li>
              <a href="campusone11@gmail.com" class="footer-link">
                <ion-icon name="mail"></ion-icon>

                <span class="footer-link-text">contact@campusone.fr</span>
              </a>
            </li>

          </ul>

          <ul class="footer-list">

            <li>
              <p class="footer-list-title">My Account</p>
            </li>

            <li>
              <a href="#" class="footer-link">
                <ion-icon name="chevron-forward-outline"></ion-icon>

                <span class="footer-link-text">My Account</span>
              </a>
            </li>

            <li>
              <a href="#" class="footer-link">
                <ion-icon name="chevron-forward-outline"></ion-icon>

                <span class="footer-link-text">View Cart</span>
              </a>
            </li>

            <li>
              <a href="#" class="footer-link">
                <ion-icon name="chevron-forward-outline"></ion-icon>

                <span class="footer-link-text">Wishlist</span>
              </a>
            </li>

            <li>
              <a href="#" class="footer-link">
                <ion-icon name="chevron-forward-outline"></ion-icon>

                <span class="footer-link-text">Compare</span>
              </a>
            </li>

            <li>
              <a href="#" class="footer-link">
                <ion-icon name="chevron-forward-outline"></ion-icon>

                <span class="footer-link-text">New Products</span>
              </a>
            </li>

          </ul>

          <div class="footer-list">

            <p class="footer-list-title">Opening Time</p>

            <table class="footer-table">
              <tbody>

                <tr class="table-row">
                  <th class="table-head" scope="row">Mon - Tue:</th>

                  <td class="table-data">8AM - 10PM</td>
                </tr>

                <tr class="table-row">
                  <th class="table-head" scope="row">Wed:</th>

                  <td class="table-data">8AM - 7PM</td>
                </tr>

                <tr class="table-row">
                  <th class="table-head" scope="row">Fri:</th>

                  <td class="table-data">7AM - 12PM</td>
                </tr>

                <tr class="table-row">
                  <th class="table-head" scope="row">Sat:</th>

                  <td class="table-data">9AM - 8PM</td>
                </tr>

                <tr class="table-row">
                  <th class="table-head" scope="row">Sun:</th>

                  <td class="table-data">Closed</td>
                </tr>

              </tbody>
            </table>

          </div>

          <div class="footer-list">

            <p class="footer-list-title">Newsletter</p>

            <p class="newsletter-text">
              Authoritatively morph 24/7 potentialities with error-free partnerships.
            </p>

            <form action="" class="newsletter-form">
              <input type="email" name="email" required placeholder="Email Address" class="newsletter-input">

              <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>

          </div>

        </div>

      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">

        <p class="copyright">
          &copy; 2025 <a href="#" class="copyright-link">CampusOne</a>. Tous droits réservés
        </p>

      </div>
    </div>

  </footer>





  <!-- 
    - #GO TO TOP
  -->

  <a href="#top" class="go-top-btn" data-go-top>
    <ion-icon name="arrow-up-outline"></ion-icon>
  </a>





  <!-- 
    - custom js link
  -->
  <script src="script.js?v=3"></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <script>
    function openCartModal() {
      document.getElementById('cartModal').style.display = 'flex';
    }

    function closeCartModal() {
      document.getElementById('cartModal').style.display = 'none';
    }

    function updateCartUI(panier) {
  // Update badge quantity
  let totalQuantity = 0;
  for (let key in panier) {
    totalQuantity += panier[key];
    // Update quantity display if available
    const quantitySpan = document.querySelector(`.cart-quantity-display[data-id="${key}"]`);
    if (quantitySpan) {
      quantitySpan.textContent = panier[key];
    }
  }
  document.querySelector('.nav-action-badge').textContent = totalQuantity;

  // Optionally update total price if needed
  // (Requires fetching product prices or recalculating from existing DOM)
}

function addToCart(productId) {
  fetch('ajouter_panier.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'produit_id=' + productId + '&quantite=1'
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      updateCartUI(data.panier);
      // Recharger la page pour mettre à jour l'affichage du panier
      location.reload();
    }
  });
}

function changeQuantity(productId, change) {
  fetch('ajouter_panier.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'produit_id=' + productId + '&quantite=' + change
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      updateCartUI(data.panier);
    }
  });
}


    function removeFromCart(productId) {
      // Envoyer une requête AJAX pour retirer le produit
      fetch('retirer_panier.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'produit_id=' + productId
      }).then(() => {
        location.reload(); // Recharger pour mettre à jour l'affichage
      });
    }

    // Fermer la modal en cliquant à l'extérieur
    document.getElementById('cartModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeCartModal();
      }
    });
  </script>

</body>

</html>
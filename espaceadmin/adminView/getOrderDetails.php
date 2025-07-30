<?php
include_once "../config/dbconnect.php";

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Récupérer les détails de la commande et le nom du client
    $sql_order = "SELECT c.*, u.name as customer_name 
                  FROM commandes c 
                  LEFT JOIN users u ON c.user_id = u.id 
                  WHERE c.id = $order_id";
    $result_order = $conn->query($sql_order);
    $order = $result_order->fetch_assoc();

    // Récupérer les lignes de commande
    $sql_items = "SELECT lc.*, p.nom as product_name 
                  FROM lignes_commande lc
                  JOIN produits p ON lc.produit_id = p.id
                  WHERE lc.commande_id = $order_id";
    $result_items = $conn->query($sql_items);
?>
    <div class="modal-header">
        <h4 class="modal-title">Détails de la Commande #<?= $order_id ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <p><strong>Client:</strong> <?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></p>
        <p><strong>Chambre de livraison:</strong> <?= htmlspecialchars($order['adresse_livraison']) ?></p>
        <p><strong>Date:</strong> <?= date("d/m/Y H:i", strtotime($order['date_commande'])) ?></p>
        <hr>
        <h5>Articles commandés</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Sous-total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $result_items->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= $item['quantite'] ?></td>
                    <td><?= number_format($item['prix_unitaire'], 2, ',', ' ') ?> €</td>
                    <td><?= number_format($item['prix_unitaire'] * $item['quantite'], 2, ',', ' ') ?> €</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <hr>
        <div class="text-right">
            <h4><strong>Total:</strong> <?= number_format($order['montant_total'], 2, ',', ' ') ?> €</h4>
        </div>
    </div>
<?php
}
?> 
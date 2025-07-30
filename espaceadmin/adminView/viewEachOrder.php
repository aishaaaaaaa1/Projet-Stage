
<div class="container py-4">
    <h2 class="mb-4">Détails de la Commande</h2>

    <?php
    include_once "../config/dbconnect.php";
    
    // Valider l'ID de la commande
    $order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);

    if($order_id){
        // Requête sécurisée pour obtenir les détails de la commande
        $sql = "SELECT p.nom, p.image_url, lc.quantite, lc.prix_unitaire 
                FROM lignes_commande lc 
                JOIN produits p ON lc.produit_id = p.id 
                WHERE lc.commande_id = ?";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h4>Commande N° " . htmlspecialchars($order_id) . "</h4>";
            echo "<table class='table table-bordered table-hover'>";
            echo "<thead class='thead-dark'><tr><th>Image</th><th>Produit</th><th>Quantité</th><th>Prix Unitaire</th><th>Total</th></tr></thead>";
            echo "<tbody>";
            
            $total_commande = 0;
            while ($row = $result->fetch_assoc()) {
                $sub_total = $row['quantite'] * $row['prix_unitaire'];
                $total_commande += $sub_total;
                echo "<tr>";
                echo "<td><img src='../" . htmlspecialchars($row['image_url']) . "' height='60' class='rounded'></td>";
                echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                echo "<td>" . $row['quantite'] . "</td>";
                echo "<td>" . number_format($row['prix_unitaire'], 2, ',', ' ') . " €</td>";
                echo "<td>" . number_format($sub_total, 2, ',', ' ') . " €</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "<tfoot><tr class='table-info'><th colspan='4' class='text-right'>Total Général :</th><th>" . number_format($total_commande, 2, ',', ' ') . " €</th></tr></tfoot>";
            echo "</table>";
        } else {
            echo "<div class='alert alert-warning'>Aucun produit trouvé pour cette commande.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>ID de commande non valide ou non spécifié.</div>";
    }
    ?>
    <a href="./viewAllOrders.php" class="btn btn-secondary mt-3">
        <i class="fa fa-arrow-left"></i> Retour à la liste des commandes
    </a>
</div> 
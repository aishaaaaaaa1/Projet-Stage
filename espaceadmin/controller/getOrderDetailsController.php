<?php
include_once "../config/dbconnect.php";

if(isset($_POST['order_id'])){
    $order_id = $_POST['order_id'];
    
    $sql = "SELECT p.nom, p.image_url, lc.quantite, lc.prix_unitaire 
            FROM lignes_commande lc 
            JOIN produits p ON lc.produit_id = p.id 
            WHERE lc.commande_id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $output = "<table class='table table-striped table-bordered'>";
        $output .= "<thead><tr><th>Image</th><th>Produit</th><th>Quantité</th><th>Prix Unitaire</th><th>Total</th></tr></thead>";
        $output .= "<tbody>";
        
        $total_commande = 0;
        while ($row = $result->fetch_assoc()) {
            $sub_total = $row['quantite'] * $row['prix_unitaire'];
            $total_commande += $sub_total;
            $output .= "<tr>";
            $output .= "<td><img src='../" . htmlspecialchars($row['image_url']) . "' height='60' style='border-radius: 5px;'></td>";
            $output .= "<td>" . htmlspecialchars($row['nom']) . "</td>";
            $output .= "<td>" . $row['quantite'] . "</td>";
            $output .= "<td>" . number_format($row['prix_unitaire'], 2) . " €</td>";
            $output .= "<td>" . number_format($sub_total, 2) . " €</td>";
            $output .= "</tr>";
        }
        
        $output .= "</tbody>";
        $output .= "<tfoot><tr><th colspan='4' class='text-right'>Total Général :</th><th class='text-left'>" . number_format($total_commande, 2) . " €</th></tr></tfoot>";
        $output .= "</table>";
        echo $output;
    } else {
        echo "<div class='alert alert-info'>Aucun produit trouvé pour cette commande.</div>";
    }
}
?> 
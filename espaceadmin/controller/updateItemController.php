<?php
include_once "../config/dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $p_name = $_POST['p_name'];
    $p_desc = $_POST['p_desc'];
    $p_price = $_POST['p_price'];
    $category_id = $_POST['category'];

    // Gestion de l'image
    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] == 0) {
        $name = $_FILES['new_image']['name'];
        $temp = $_FILES['new_image']['tmp_name'];
        $location = "../uploads/";
        $finalImage = $location . $name;
        move_uploaded_file($temp, $finalImage);
        
        $sql = "UPDATE produits SET 
                nom='$p_name', 
                description='$p_desc', 
                prix='$p_price', 
                categorie_id='$category_id', 
                image_url='uploads/$name' 
                WHERE id=$product_id";
    } else {
        // Si aucune nouvelle image n'est téléchargée, on ne met pas à jour le champ image_url
        $sql = "UPDATE produits SET 
                nom='$p_name', 
                description='$p_desc', 
                prix='$p_price', 
                categorie_id='$category_id' 
                WHERE id=$product_id";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: ../adminView/viewAllProducts.php?success=2");
    } else {
        echo "Erreur lors de la mise à jour: " . mysqli_error($conn);
    }
}
?>
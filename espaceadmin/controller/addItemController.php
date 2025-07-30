<?php
    include_once "../config/dbconnect.php";
    
    if(isset($_POST['upload']))
    {
       
        $product_name = $_POST['p_name'];
        $product_desc = $_POST['p_desc'];
        $product_price = $_POST['p_price'];
        $category = $_POST['category'];

        $name = $_FILES['file']['name'];
        $temp = $_FILES['file']['tmp_name'];
    
        $location="./uploads/";
        $image=$location.$name;

        $target_dir = "../uploads/";
        $finalImage=$target_dir.$name;

        move_uploaded_file($temp,$finalImage);

        $insert = mysqli_query($conn,"INSERT INTO produits
         (nom, prix, description, categorie_id, image_url) 
         VALUES ('$product_name','$product_price','$product_desc','$category','$finalImage')");
 
         if(!$insert)
         {
             echo mysqli_error($conn);
             // Consider adding a redirect with an error message
             // header("Location: ../adminView/viewAllProducts.php?error=1");
         }
         else
         {
             header("Location: ../adminView/viewAllProducts.php?success=1");
         }
     
    }
        
?>
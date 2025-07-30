<?php
    include_once "../config/dbconnect.php";
    
    if(isset($_POST['upload']))
    {
       
        $catname = $_POST['c_name'];
        $cattype = $_POST['c_type'];
       
        // Vérifier si la colonne type existe
        $checkColumn = "SHOW COLUMNS FROM category LIKE 'type'";
        $result = $conn->query($checkColumn);
        
        if ($result->num_rows > 0) {
            // La colonne existe, on peut l'utiliser
            $insert = mysqli_query($conn,"INSERT INTO category
            (category_name, type) 
            VALUES ('$catname', '$cattype')");
        } else {
            // La colonne n'existe pas, on insère sans le type
            $insert = mysqli_query($conn,"INSERT INTO category
            (category_name) 
            VALUES ('$catname')");
        }
 
         if(!$insert)
         {
             header("Location: ../adminView/viewCategories.php?error=1");
         }
         else
         {
             header("Location: ../adminView/viewCategories.php?success=1");
         }
     
    }
        
?>
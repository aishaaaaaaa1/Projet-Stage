<?php

    include_once "../config/dbconnect.php";
    
    $c_id=$_GET['record'];
    $query="DELETE FROM category where category_id='$c_id'";

    $data=mysqli_query($conn,$query);

    if($data){
        header("Location: ../index.php?delete=success");
    }
    else{
        header("Location: ../index.php?delete=error");
    }
    
?>
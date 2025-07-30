
<div class="container p-5">

<h4>Edit Product Detail</h4>
<?php
    include_once "../config/dbconnect.php";
	$ID = $_POST['record'];
	$qry = mysqli_query($conn, "SELECT * FROM produits WHERE id='$ID'");
	$numberOfRow = mysqli_num_rows($qry);
	if($numberOfRow > 0){
		while($row1 = mysqli_fetch_array($qry)){
      $catID=$row1["categorie_id"];
?>
<form id="update-Items" onsubmit="updateItems()" enctype='multipart/form-data'>
	<div class="form-group">
      <input type="text" class="form-control" id="product_id" value="<?=$row1['id']?>" hidden>
    </div>
    <div class="form-group">
      <label for="name">Nom du produit:</label>
      <input type="text" class="form-control" id="p_name" value="<?=$row1['nom']?>">
    </div>
    <div class="form-group">
      <label for="desc">Description:</label>
      <input type="text" class="form-control" id="p_desc" value="<?=$row1['description']?>">
    </div>
    <div class="form-group">
      <label for="price">Prix:</label>
      <input type="number" class="form-control" id="p_price" value="<?=$row1['prix']?>">
    </div>
    <div class="form-group">
      <label>Catégorie:</label>
      <select id="category" class="form-control">
        <?php
          $sql="SELECT * from categories";
          $result = $conn-> query($sql);
          if ($result-> num_rows > 0){
            while($row = $result-> fetch_assoc()){
              $selected = ($row['id'] == $catID) ? "selected" : "";
              echo "<option value='".$row['id']."' ".$selected.">".$row['nom']."</option>";
            }
          }
        ?>
      </select>
    </div>
    <div class="form-group">
      <img width='200px' height='150px' src='../<?=$row1["image_url"]?>'>
      <div>
        <label for="file">Choisir une nouvelle image:</label>
        <input type="file" id="new_image" value="">
      </div>
    </div>
    <div class="form-group">
      <button type="submit" style="height:40px;" class="btn btn-primary">Modifier Produit</button>
    </div>
    <?php
    		}
    	}
    ?>
  </form>

    
<script>
    function updateItems(){
      // Mettez à jour la logique ici avec AJAX
    }
</script>

    </div>
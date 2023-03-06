<?php
session_start();
require('connection.php');
if(!isset($_SESSION['logged_in_seller']) || $_SESSION['logged_in_seller'] != true){
    header('Location: seller-login.php');
}else{
   $mid = $_SESSION['mid'];

if(isset($_POST['add_product'])){
   $p_id = $_POST['p_id'];
   $p_price = $_POST['p_price'];
   $insert_query = mysqli_query($link, "INSERT INTO markets_products(market_id, product_id, price) VALUES('$mid', '$p_id', '$p_price')") or die('query failed');

   if($insert_query){
      $message[] = 'product add succesfully';
   }else{
      $message[] = 'could not add the product';
   }
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_query = mysqli_query($link, "DELETE FROM markets_products WHERE combination_id = $delete_id ") or die('query failed');
   if($delete_query){
      header('location:seller.php');
      $message[] = 'product has been deleted';
   }else{
      header('location:seller.php');
      $message[] = 'product could not be deleted';
   };
};

if(isset($_POST['update_product'])){
   $update_p_id = $_POST['update_p_id'];
   $update_p_price = $_POST['update_p_price'];
   if(!$update_p_price){
      $update_p_price = 0;
   }
   $update_query = mysqli_query($link, "UPDATE markets_products SET price = '$update_p_price' WHERE combination_id = '$update_p_id'");

   if($update_query){
      $message[] = 'product updated succesfully';
      header('location:seller.php');
   }else{
      $message[] = 'product could not be updated';
      header('location:seller.php');
   }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Waffer Seller</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/seller-style.css">

</head>
<body>
   
<?php

if(isset($message)){
   foreach($message as $message){
      echo '<div class="message"><span>'.$message.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
   };
};

$result = mysqli_query($link,"SELECT market_name FROM markets WHERE market_id = $mid");
$row = mysqli_fetch_assoc($result);
?>

<header class="header">

   <div class="flex">

      <a href="seller.php" class="logo"><i class="fas fa-shopping-basket"></i> Waffer</a>
      <h2>Welcome <?=$_SESSION['seller-name']?> | <?=$row['market_name']?></h2>
      <nav class="navbar">
         <a href="addcategory.php">categories</a>
         <a href="addproduct.php">products</a>
         <a href="password-seller.php">Password</a>
         <a href="logout.php">Logout</a>
      </nav>

      <div id="menu-btn" class="fas fa-bars"></div>

   </div>

</header>

<div class="container">

<section>

<form action="" method="post" class="add-product-form" enctype="multipart/form-data" onsubmit="return validateForm2();">
   <h3>add a new product</h3>
   <select id="categories" name="category" class="box" onchange="cat(this);">
      <option selected="true" disabled="disabled" value="0">Select Category</option>
      <?php
      $result=mysqli_query($link,"SELECT * FROM categories");
      while($row = mysqli_fetch_assoc($result)){
         echo '<option value = "'.$row['category_id'].'">'.$row['category_name'].'</option>';
      }
      ?>
   </select>
   <select id="products" name="p_id" class="box">
      <option selected="true" disabled="disabled" value="0">Select product</option>
   </select>
   <input type="number" name="p_price" min="0" placeholder="enter the product price" class="box" required>
   <input type="submit" value="add the product" name="add_product" class="btn">
</form>

</section>

<section class="display-product-table">

   <table>

      <thead>
         <th>product image</th>
         <th>product name</th>
         <th>product price</th>
         <th>category</th>
         <th>action</th>
      </thead>

      <tbody>
         <?php
         
            $select_products = mysqli_query($link, "SELECT * FROM ((((products INNER JOIN markets_products ON products.product_id = markets_products.product_id) 
            INNER JOIN markets ON markets_products.market_id = markets.market_id) INNER JOIN images ON products.image_id = images.image_id)
             INNER JOIN categories ON products.category_id = categories.category_id) WHERE markets.market_id = $mid ORDER BY products.category_id;");
            if(mysqli_num_rows($select_products) > 0){
               while($row = mysqli_fetch_assoc($select_products)){
               $imageURL = 'image/products/'.substr($row['file_name'],8);
         ?>

         <tr>
            <td><img src="<?= $imageURL ?>" height="100" alt="<?=$row['product_name']?> image"></td>
            <td><?php echo $row['product_name'].' - '.$row['description']; ?></td>
            <td><?php echo $row['price']; ?> L.L.</td>
            <td><?php echo $row['category_name']; ?></td>
            <td>
               <a href="seller.php?delete=<?php echo $row['combination_id']; ?>" class="delete-btn" onclick="return confirm('are your sure you want to delete this?');"> <i class="fas fa-trash"></i> delete </a>
               <a href="seller.php?edit=<?php echo $row['combination_id']; ?>" class="option-btn"> <i class="fas fa-edit"></i> update </a>
            </td>
         </tr>

         <?php
            };    
            }else{
               echo "<div class='empty'>no product added</div>";
            };
         ?>
      </tbody>
   </table>

</section>

<section class="edit-form-container">

   <?php
   
   if(isset($_GET['edit'])){
      $edit_id = $_GET['edit'];
      $edit_query = mysqli_query($link, "SELECT * FROM (markets_products INNER JOIN products ON markets_products.product_id=products.product_id) WHERE combination_id = $edit_id");
      if(mysqli_num_rows($edit_query) > 0){
         while($fetch_edit = mysqli_fetch_assoc($edit_query)){
   ?>

   <form action="" method="post" enctype="multipart/form-data">
      <h2><?=$fetch_edit['product_name']?></h2>
      <input type="hidden" name="update_p_id" value="<?=$edit_id ?>">
      <input type="number" min="0" class="box" required name="update_p_price" value="<?php echo $fetch_edit['price']; ?>">
      <input type="submit" value="update the product" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-edit" class="option-btn">
   </form>

   <?php
            };
         };
         echo "<script>document.querySelector('.edit-form-container').style.display = 'flex';</script>";
      };
   ?>

</section>

</div>












<!-- custom js file link  -->
<script src="js/script_seller.js"></script>

</body>
<?php } ?>
</html>
<?php
session_start();
require('connection.php');
if(!isset($_SESSION['logged_in_seller']) || $_SESSION['logged_in_seller'] != true){
    header('Location: seller-login.php');
}else{

    if(isset($_POST['add_product'])){
        $p_name = $_POST['p_name'];
        $desc = $_POST['p_desc'];
        $c_id = $_POST['categories'];
            // File upload path
        $targetDir = "image/products/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

            // Upload file to server
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $fileNamedata = 'product_'.$fileName;
            $query = "INSERT into images (file_name, uploaded_on) VALUES ('".$fileNamedata."', NOW())";
            $insert = mysqli_query($link,$query);
            if($insert){
                $result=mysqli_query($link,"SELECT image_id FROM images ORDER BY image_id DESC LIMIT 1");
                if($result){
                $row=mysqli_fetch_row($result);
                $image_id = $row[0];
                $insert_query = mysqli_query($link, "INSERT INTO products(product_name, description, image_id, category_id) VALUES('$p_name', '$desc', '$image_id', '$c_id')") or die('query failed');
            
                if($insert_query){
                $message[] = 'product added succesfully';
                }else{
                $message[] = 'could not add the product';
                }
            }else{
                $message[] = 'error image';
            }
            }else{
                $message[] = "File upload failed, please try again.";
            } 
        }else{
            $message[] = "Sorry, there was an error uploading your file.";
        }
     };

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
$mid = $_SESSION['mid'];
$result = mysqli_query($link,"SELECT market_name FROM markets WHERE market_id = $mid");
$row = mysqli_fetch_assoc($result);
?>

<header class="header">

   <div class="flex">

      <a href="seller.php" class="logo"><i class="fas fa-shopping-basket"></i> Waffer</a>
      <h2>Welcome <?=$_SESSION['seller-name']?> | <?=$row['market_name']?></h2>
      <nav class="navbar">
        <a href="seller.php">Go Back</a>
        <a href="addcategory.php">Categories</a>
        <a href="password-seller.php">Password</a>
        <a href="logout.php">Logout</a>
      </nav>

      <div id="menu-btn" class="fas fa-bars"></div>

   </div>

</header>

<div class="container">

<section>

<form action="" method="post" class="add-product-form" enctype="multipart/form-data" onsubmit="return validateForm();">
   <h3>add a new product</h3>
   <select name="categories" id="categories" class="box" onchange="cat(this);">
      <option selected="true" disabled="disabled" value="0">Select Category</option>
      <?php
      $result=mysqli_query($link,"SELECT * FROM categories");
      while($row = mysqli_fetch_assoc($result)){
         echo '<option value = "'.$row['category_id'].'">'.$row['category_name'].'</option>';
      }
      ?>
   </select>
   <input type="text" name="p_name" placeholder="enter the product name" class="box" required>
   <input type="text" name="p_desc" placeholder="enter description" class="box" required>
   <input type="file" name="file" accept="image/png, image/jpg, image/jpeg" class="box" required>
   <input type="submit" value="add the product" name="add_product" class="btn">
</form>

</section>

<section class="display-product-table">

   <table>

      <thead>
         <th>product image</th>
         <th>product name</th>
         <th>description</th>
         <th>category</th>
      </thead>

      <tbody>
         <?php
         
            $select_products = mysqli_query($link, "SELECT * FROM ((products INNER JOIN images ON products.image_id=images.image_id) INNER JOIN categories ON categories.category_id=products.category_id) ORDER BY products.category_id");
            if(mysqli_num_rows($select_products) > 0){
               while($row = mysqli_fetch_assoc($select_products)){
               $imageURL = 'image/products/'.substr($row['file_name'],8);
         ?>

         <tr>
            <td><img src="<?= $imageURL ?>" height="100" alt="<?=$row['product_name']?> image"></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['category_name']; ?></td>
         </tr>
         <?php
            };    
            }else{
               echo "<div class='empty'>no products yet</div>";
            };
         ?>
      </tbody>
   </table>

</section>
</div>












<!-- custom js file link  -->
<script src="js/script_seller.js"></script>

</body>
<?php } ?>
</html>
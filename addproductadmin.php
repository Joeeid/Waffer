<?php
session_start();
require('connection.php');
if(!isset($_SESSION['logged_in_admin']) || $_SESSION['logged_in_admin'] != true){
    header('Location: admin-login.php');
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

     if(isset($_GET['delete'])){
        $delete_id = $_GET['delete'];
        $delete_query = mysqli_query($link, "DELETE FROM markets_products WHERE product_id = $delete_id ") or die('query failed');
        if($delete_query){
           $delete_query2 = mysqli_query($link, "DELETE FROM products WHERE product_id = $delete_id ") or die('query failed');
           if($delete_query2){
           header('location:addproductadmin.php');
           $message[] = 'product has been deleted';
           }else{
             header('location:addproductadmin.php');
             $message[] = 'product could not be deleted';
          }
        }else{
           header('location:addproductadmin.php');
           $message[] = 'market-product could not be deleted';
        };
     };

     if(isset($_POST['update_product'])){
        extract($_POST);
        if(file_exists($_FILES['file']['tmp_name']) || is_uploaded_file($_FILES['file']['tmp_name'])) {
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
                    $update_query = mysqli_query($link, "UPDATE products SET product_name = '$p_name', description = '$desc', image_id = '$image_id', category_id = '$category' WHERE product_id=$pid") or die('query failed');
                    if($update_query){
                            $message[] = 'product updated succesfully';
                        }else{
                            $message[] = 'could not update the product';
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
    }else{
        $update_query = mysqli_query($link, "UPDATE products SET product_name = '$p_name', description = '$desc', category_id = '$category' WHERE product_id=$pid") or die('query failed');
        if($update_query){
            $message[] = 'product updated succesfully';
        }else{
            $message[] = 'could not update the product';
        }
    }
    }

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Waffer Admin</title>

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
?>

<header class="header">

   <div class="flex">

      <a href="admin.php" class="logo"><i class="fas fa-shopping-basket"></i> Waffer</a>
      <h2>Welcome <?=$_SESSION['admin-name']?></h2>
      <nav class="navbar">
        <a href="admin.php">Go Back</a>
        <a href="addcategoryadmin.php">Add/View Categories</a>
        <a href="delivery.php">Delivery</a>
        <a href="users.php">Users</a>
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
         <th>action</th>
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
            <td>
               <a href="addproductadmin.php?delete=<?php echo $row['product_id']; ?>" class="delete-btn" onclick="return confirm('are your sure you want to delete this?');"> <i class="fas fa-trash"></i> delete </a>
               <a href="addproductadmin.php?edit=<?php echo $row['product_id']; ?>" class="option-btn"> <i class="fas fa-edit"></i> update </a>
            </td>
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

<section class="edit-form-container" style="height: px; overflow: auto;">

   <?php
   
   if(isset($_GET['edit'])){
      $edit_id = $_GET['edit'];
      $edit_query = mysqli_query($link, "SELECT * FROM (products p INNER JOIN images i ON p.image_id=i.image_id) WHERE product_id = $edit_id");
      if(mysqli_num_rows($edit_query) > 0){
         while($fetch_edit = mysqli_fetch_assoc($edit_query)){
   ?>

    <form action="" method="post" enctype="multipart/form-data">
        <?php $imageURL = 'image/products/'.substr($fetch_edit['file_name'],8); ?>
        <img src="<?=$imageURL ?>" height="200" alt="">
        <input type="hidden" name="pid" value="<?php echo $fetch_edit['product_id']; ?>">
        <input type="text" name="p_name" value="<?=$fetch_edit['product_name']?>" class="box" required>
        <input type="text" name="desc" value="<?=$fetch_edit['description']?>" class="box" required>
        <select name="category" class="box" id="category">
            <?php
            $result=mysqli_query($link,"SELECT * FROM categories");
            while($row = mysqli_fetch_assoc($result)){
               echo '<option value = "'.$row['category_id'].'" ';
               if($fetch_edit['category_id'] == $row['category_id']){echo("selected");}
               echo '>'.$row['category_name'].'</option>';
            }
            ?>
        </select>
        <input type="file" name="file" accept="image/png, image/jpg, image/jpeg" class="box">
        <input type="submit" value="update" name="update_product" class="btn">
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

<script>
    document.querySelector('#close-edit').onclick = () =>{
   document.querySelector('.edit-form-container').style.display = 'none';
   window.location.href = 'addproductadmin.php';
};
</script>

<!-- custom js file link  -->
<script src="js/script_admin.js"></script>

</body>
<?php } ?>
</html>
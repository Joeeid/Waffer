<?php
session_start();
require('connection.php');
if(!isset($_SESSION['logged_in_admin']) || $_SESSION['logged_in_admin'] != true){
    header('Location: admin-login.php');
}else{

    if(isset($_POST['add_category'])){
        $c_name = $_POST['c_name'];
            // File upload path
        $targetDir = "image/categories/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

            // Upload file to server
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $fileNamedata = 'category_'.$fileName;
            $query = "INSERT into images (file_name, uploaded_on) VALUES ('".$fileNamedata."', NOW())";
            $insert = mysqli_query($link,$query);
            if($insert){
                $result=mysqli_query($link,"SELECT image_id FROM images ORDER BY image_id DESC LIMIT 1");
                if($result){
                $row=mysqli_fetch_row($result);
                $image_id = $row[0];
                $insert_query = mysqli_query($link, "INSERT INTO categories(category_name, image_id) VALUES('$c_name', '$image_id')") or die('query failed');
            
                if($insert_query){
                $message[] = 'category added succesfully';
                }else{
                $message[] = 'could not add the category';
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
      $delete_query = mysqli_query($link, "DELETE FROM products WHERE category_id = $delete_id ");
      if($delete_query){
         $delete_query2 = mysqli_query($link, "DELETE FROM categories WHERE category_id = $delete_id ");
         if($delete_query2){
         header('location:addcategoryadmin.php');
         $message[] = 'category has been deleted';
         }else{
           header('location:addcategoryadmin.php');
           $message[] = 'category could not be deleted';
        }
      }else{
         header('location:addcategoryadmin.php');
         $message[] = 'products could not be deleted';
      };
   };

   if(isset($_POST['update_category'])){
      extract($_POST);
      if(file_exists($_FILES['file']['tmp_name']) || is_uploaded_file($_FILES['file']['tmp_name'])) {
      // File upload path
      $targetDir = "image/categories/";
      $fileName = basename($_FILES["file"]["name"]);
      $targetFilePath = $targetDir . $fileName;
      $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
  
          // Upload file to server
          if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
          // Insert image file name into database
          $fileNamedata = 'category_'.$fileName;
          $query = "INSERT into images (file_name, uploaded_on) VALUES ('".$fileNamedata."', NOW())";
          $insert = mysqli_query($link,$query);
          if($insert){
              $result=mysqli_query($link,"SELECT image_id FROM images ORDER BY image_id DESC LIMIT 1");
              if($result){
                  $row=mysqli_fetch_row($result);
                  $image_id = $row[0];
                  $update_query = mysqli_query($link, "UPDATE categories SET category_name = '$c_name', image_id = '$image_id' WHERE category_id=$cid");
                  if($update_query){
                          $message[] = 'category updated succesfully';
                      }else{
                          $message[] = 'could not update the category';
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
      $update_query = mysqli_query($link, "UPDATE categories SET category_name = '$c_name' WHERE category_id=$cid");
      if($update_query){
          $message[] = 'category updated succesfully';
      }else{
          $message[] = 'could not update the category';
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
         <a href="addproductadmin.php">Add/View products</a>
         <a href="delivery.php">Delivery</a>
         <a href="users.php">Users</a>
         <a href="logout.php">Logout</a>
      </nav>

      <div id="menu-btn" class="fas fa-bars"></div>

   </div>

</header>

<div class="container">

<section>

<form action="" method="post" class="add-product-form" enctype="multipart/form-data">
   <h3>add a new category</h3>
   <input type="text" name="c_name" placeholder="enter the category name" class="box" required>
   <input type="file" name="file" accept="image/png, image/jpg, image/jpeg" class="box" required>
   <input type="submit" value="add the category" name="add_category" class="btn">
</form>

</section>

<section class="display-product-table">

   <table>

      <thead>
         <th>category image</th>
         <th>category</th>
         <th>action</th>
      </thead>

      <tbody>
         <?php
         
            $select_categories = mysqli_query($link, "SELECT * FROM (categories INNER JOIN images ON categories.image_id=images.image_id)");
            if(mysqli_num_rows($select_categories) > 0){
               while($row = mysqli_fetch_assoc($select_categories)){
               $imageURL = 'image/categories/'.substr($row['file_name'],9);
         ?>

         <tr>
            <td><img src="<?= $imageURL ?>" height="100" alt="<?=$row['category_name']?> image"></td>
            <td><?php echo $row['category_name']; ?></td>
            <td>
               <a href="addcategoryadmin.php?delete=<?php echo $row['category_id']; ?>" class="delete-btn" onclick="return confirm('are your sure you want to delete this? Note that ALL the PRODUCTS in this category will be DELETED!');"> <i class="fas fa-trash"></i> delete </a>
               <a href="addcategoryadmin.php?edit=<?php echo $row['category_id']; ?>" class="option-btn"> <i class="fas fa-edit"></i> update </a>
            </td>
         </tr>
         <?php
            };    
            }else{
               echo "<div class='empty'>no categories yet</div>";
            };
         ?>
      </tbody>
   </table>

</section>

<section class="edit-form-container" style="height: px; overflow: auto;">

   <?php
   
   if(isset($_GET['edit'])){
      $edit_id = $_GET['edit'];
      $edit_query = mysqli_query($link, "SELECT * FROM (categories c INNER JOIN images i ON c.image_id=i.image_id) WHERE category_id = $edit_id");
      if(mysqli_num_rows($edit_query) > 0){
         while($fetch_edit = mysqli_fetch_assoc($edit_query)){
   ?>

    <form action="" method="post" enctype="multipart/form-data">
        <?php $imageURL = 'image/categories/'.substr($fetch_edit['file_name'],9); ?>
        <img src="<?=$imageURL ?>" height="200" alt="">
        <input type="hidden" name="cid" value="<?php echo $fetch_edit['category_id']; ?>">
        <input type="text" name="c_name" value="<?=$fetch_edit['category_name']?>" class="box" required>
        <input type="file" name="file" accept="image/png, image/jpg, image/jpeg" class="box">
        <input type="submit" value="update" name="update_category" class="btn">
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
   window.location.href = 'addcategoryadmin.php';
};
</script>

<!-- custom js file link  -->
<script src="js/script_admin.js"></script>

</body>
<?php } ?>
</html>
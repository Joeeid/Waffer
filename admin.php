<?php
session_start();
require('connection.php');
if(!isset($_SESSION['logged_in_admin']) || $_SESSION['logged_in_admin'] != true){
    header('Location: admin-login.php');
}else{

if(isset($_POST['add_seller'])){
    extract($_POST);
    // File upload path
    $targetDir = "image/markets/";
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
        // Insert image file name into database
        $fileNamedata = 'market_'.$fileName;
        $query = "INSERT into images (file_name, uploaded_on) VALUES ('".$fileNamedata."', NOW())";
        $insert = mysqli_query($link,$query);
        if($insert){
            $result=mysqli_query($link,"SELECT image_id FROM images ORDER BY image_id DESC LIMIT 1");
            if($result){
                $row=mysqli_fetch_row($result);
                $image_id = $row[0];
                $insert_query = mysqli_query($link, "INSERT INTO markets(market_name, slogan, website, location, image_id) VALUES('$m_name', '$m_slogan', '$m_website', '$location', '$image_id')") or die('query failed');
                if($insert_query){
                    $result=mysqli_query($link,"SELECT market_id FROM markets ORDER BY market_id DESC LIMIT 1");
                    if($result){
                    $row=mysqli_fetch_row($result);
                    $mid = $row[0];
                    $pass = md5($s_pass);
                    $insert_query2 = mysqli_query($link, "INSERT INTO sellers(name, email, password, market_id) VALUES('$s_name', '$s_email', '$pass', '$mid')") or die('query failed');
                    if($insert_query2){
                        $message[] = 'seller added succesfully';
                    }else{
                        $message[] = 'could not add the seller';
                    }
                }else{
                    $message[] = 'error market id';
                }
            }else{
                $message[] = 'error market';
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
   $delete_query = mysqli_query($link, "DELETE FROM markets WHERE market_id = $delete_id ") or die('query failed');
   if($delete_query){
      $delete_query2 = mysqli_query($link, "DELETE FROM sellers WHERE market_id = $delete_id ") or die('query failed');
      if($delete_query2){
      header('location:admin.php');
      $message[] = 'seller has been deleted';
      }else{
        header('location:admin.php');
        $message[] = 'seller could not be deleted';
     }
   }else{
      header('location:admin.php');
      $message[] = 'market could not be deleted';
   };
};

if(isset($_POST['update_seller'])){
    extract($_POST);
    if(file_exists($_FILES['file']['tmp_name']) || is_uploaded_file($_FILES['file']['tmp_name'])) {
    // File upload path
    $targetDir = "image/markets/";
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
        // Insert image file name into database
        $fileNamedata = 'market_'.$fileName;
        $query = "INSERT into images (file_name, uploaded_on) VALUES ('".$fileNamedata."', NOW())";
        $insert = mysqli_query($link,$query);
        if($insert){
            $result=mysqli_query($link,"SELECT image_id FROM images ORDER BY image_id DESC LIMIT 1");
            if($result){
                $row=mysqli_fetch_row($result);
                $image_id = $row[0];
                $update_query = mysqli_query($link, "UPDATE markets SET market_name = '$m_name', slogan = '$m_slogan', website = '$m_website', location = '$location', image_id = '$image_id' WHERE market_id=$mid") or die('query failed');
                if($update_query){
                    if(!$s_pass){
                        $update_query2 = mysqli_query($link, "UPDATE sellers SET name = '$s_name', email = '$s_email', market_id = '$mid' WHERE market_id=$mid") or die('query failed');
                    }else{
                        $pass = md5($s_pass);
                        $update_query2 = mysqli_query($link, "UPDATE sellers SET name = '$s_name', email = '$s_email', password = '$pass', market_id = '$mid' WHERE market_id=$mid") or die('query failed');
                    }
                    if($update_query2){
                        $message[] = 'seller updated succesfully';
                    }else{
                        $message[] = 'could not update the seller';
                    }
            }else{
                $message[] = 'error market';
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
    $update_query = mysqli_query($link, "UPDATE markets SET market_name = '$m_name', slogan = '$m_slogan', website = '$m_website', location = '$location' WHERE market_id=$mid") or die('query failed');
            if($update_query){
                if(!$s_pass){
                    $update_query2 = mysqli_query($link, "UPDATE sellers SET name = '$s_name', email = '$s_email', market_id = '$mid' WHERE market_id=$mid") or die('query failed');
                }else{
                    $pass = md5($s_pass);
                    $update_query2 = mysqli_query($link, "UPDATE sellers SET name = '$s_name', email = '$s_email', password = '$pass', market_id = '$mid' WHERE market_id=$mid") or die('query failed');
                }
                if($update_query2){
                    $message[] = 'seller updated succesfully';
                }else{
                    $message[] = 'could not update the seller';
                }
        }else{
            $message[] = 'error market';
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
         <a href="addcategoryadmin.php">Add/View categories</a>
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
   <h3>add a new seller/market</h3>
   <input type="text" name="s_name" placeholder="enter seller's name" class="box" required>
   <input type="email" name="s_email" placeholder="enter seller's email" class="box" required>
   <input type="text" name="m_name" placeholder="enter market's name" class="box" required>
   <input type="text" name="m_slogan" placeholder="enter the slogan" class="box" required>
   <input type="text" name="m_website" placeholder="enter market's website" class="box" required>
   <select name="location" class="box" id="location">
   <option value="beirut">Beirut</option>
   <option value="mount lebanon">Mount Lebanon</option>
   <option value="north lebanon">North Lebanon</option>
   <option value="akkar">Akkar</option>
   <option value="baalbek">Baalbek</option>
   <option value="beqaa">Beqaa</option>
   <option value="south lebanon">South Lebanon</option>
   <option value="Nabatieh">Nabatieh</option>
   </select>
   <input type="file" name="file" accept="image/png, image/jpg, image/jpeg" class="box" required>
   <input type="text" name="s_pass" placeholder="create a password" class="box" required>
   <input type="submit" value="add" name="add_seller" class="btn">
</form>

</section>

<section class="display-product-table">

   <table>

      <thead>
         <th>market image</th>
         <th>market name</th>
         <th>seller name</th>
         <th>email</th>
         <th>action</th>
      </thead>

      <tbody>
         <?php
         
            $select_sellers = mysqli_query($link, "SELECT * FROM ((sellers s INNER JOIN markets m ON s.market_id=m.market_id) INNER JOIN images i ON i.image_id=m.image_id)");
            if(mysqli_num_rows($select_sellers) > 0){
               while($row = mysqli_fetch_assoc($select_sellers)){
               $imageURL = 'image/markets/'.substr($row['file_name'],7);
         ?>

         <tr>
            <td><img src="<?= $imageURL ?>" height="100" alt="<?=$row['market_name']?> image"></td>
            <td><?php echo $row['market_name']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
               <a href="admin.php?delete=<?php echo $row['market_id']; ?>" class="delete-btn" onclick="return confirm('are your sure you want to delete this?');"> <i class="fas fa-trash"></i> delete </a>
               <a href="admin.php?edit=<?php echo $row['market_id']; ?>" class="option-btn"> <i class="fas fa-edit"></i> update </a>
            </td>
         </tr>

         <?php
            };    
            }else{
               echo "<div class='empty'>no sellers added</div>";
            };
         ?>
      </tbody>
   </table>

</section>

<section class="edit-form-container" style="height: px; overflow: auto;">

   <?php
   
   if(isset($_GET['edit'])){
      $edit_id = $_GET['edit'];
      $edit_query = mysqli_query($link, "SELECT * FROM ((markets m INNER JOIN sellers s ON m.market_id=s.market_id) INNER JOIN images i ON m.image_id=i.image_id) WHERE m.market_id = $edit_id");
      if(mysqli_num_rows($edit_query) > 0){
         while($fetch_edit = mysqli_fetch_assoc($edit_query)){
   ?>

    <form action="" method="post" enctype="multipart/form-data">
        <? $imageURL = 'image/markets/'.substr($fetch_edit['file_name'],7); ?>
        <img src="<?=$imageURL ?>" height="200" alt="">
        <input type="hidden" name="mid" value="<?php echo $fetch_edit['market_id']; ?>">
        <input type="text" name="s_name" value="<?=$fetch_edit['name']?>" class="box" required>
        <input type="email" name="s_email" value="<?=$fetch_edit['email']?>" class="box" required>
        <input type="text" name="m_name" value="<?=$fetch_edit['market_name']?>" class="box" required>
        <input type="text" name="m_slogan" value="<?=$fetch_edit['slogan']?>" class="box" required>
        <input type="text" name="m_website" value="<?=$fetch_edit['website']?>" class="box" required>
        <select name="location" class="box" id="location">
            <option value="beirut" <?php if($fetch_edit['location'] == 'beirut'){echo("selected");}?>>Beirut</option>
            <option value="mount lebanon" <?php if($fetch_edit['location'] == 'mount lebanon'){echo("selected");}?>>Mount Lebanon</option>
            <option value="north lebanon" <?php if($fetch_edit['location'] == 'north lebanon'){echo("selected");}?>>North Lebanon</option>
            <option value="akkar" <?php if($fetch_edit['location'] == 'akkar'){echo("selected");}?>>Akkar</option>
            <option value="baalbek" <?php if($fetch_edit['location'] == 'baalbek'){echo("selected");}?>>Baalbek</option>
            <option value="beqaa" <?php if($fetch_edit['location'] == 'beqaa'){echo("selected");}?>>Beqaa</option>
            <option value="south lebanon" <?php if($fetch_edit['location'] == 'south lebanon'){echo("selected");}?>>South Lebanon</option>
            <option value="nabatieh" <?php if($fetch_edit['location'] == 'nabatieh'){echo("selected");}?>>Nabatieh</option>
        </select>
        <input type="file" name="file" accept="image/png, image/jpg, image/jpeg" class="box">
        <input type="text" name="s_pass" placeholder="update password" class="box">
        <input type="submit" value="update" name="update_seller" class="btn">
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
   window.location.href = 'admin.php';
};
</script>

<!-- custom js file link  -->
<script src="js/script_admin.js"></script>

</body>
<?php } ?>
</html>
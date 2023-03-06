<?php
session_start();
require('connection.php');
if(!isset($_SESSION['logged_in_admin']) || $_SESSION['logged_in_admin'] != true){
    header('Location: admin-login.php');
}else{

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_query = mysqli_query($link, "DELETE FROM users WHERE user_id = $delete_id ") or die('query failed');
   if($delete_query){
      header('location:users.php');
      $message[] = 'user has been deleted';
   }else{
      header('location:users.php');
      $message[] = 'user could not be deleted';
   };
};

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
         <a href="addcategoryadmin.php">Add/View categories</a>
         <a href="addproductadmin.php">Add/View products</a>
         <a href="delivery.php">Delivery</a>
         <a href="logout.php">Logout</a>
      </nav>

      <div id="menu-btn" class="fas fa-bars"></div>

   </div>

</header>

<div class="container">

<section class="display-product-table">

   <table>

      <thead>
         <th>Name</th>
         <th>Number</th>
         <th>Email</th>
         <th>Address</th>
         <th>Status</th>
         <th>action</th>
      </thead>

      <tbody>
         <?php
         
            $select_users = mysqli_query($link, "SELECT * FROM users");
            if(mysqli_num_rows($select_users) > 0){
               while($row = mysqli_fetch_assoc($select_users)){
         ?>

         <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['number']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
               <a href="users.php?delete=<?php echo $row['user_id']; ?>" class="delete-btn" onclick="return confirm('are your sure you want to delete this user?');"> <i class="fas fa-trash"></i> delete </a>
            </td>
         </tr>

         <?php
            };    
            }else{
               echo "<div class='empty'>no users added</div>";
            };
         ?>
      </tbody>
   </table>

</section>

</div>

<!-- custom js file link  -->
<script src="js/script_admin.js"></script>

</body>
<?php } ?>
</html>
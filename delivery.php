<?php
session_start();
require('connection.php');
if(!isset($_SESSION['logged_in_admin']) || $_SESSION['logged_in_admin'] != true){
    header('Location: admin-login.php');
}else{

    if(isset($_POST['update'])){
        extract($_POST);
        $result=mysqli_query($link,"UPDATE delivery SET charge = '$charge' WHERE (dest1='$dest1' AND dest2='$dest2') OR (dest1='$dest2' AND dest2='$dest1')");
        if($result){
            $message[] = 'delivery charge has been updated';
        }else{
            $message[] = 'could not update delivery charge';
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
         <a href="addcategoryadmin.php">Add/View categories</a>
         <a href="users.php">Users</a>
         <a href="logout.php">Logout</a>
      </nav>

      <div id="menu-btn" class="fas fa-bars"></div>

   </div>

</header>

<div class="container">

<section>

<form action="" method="post" class="add-product-form" enctype="multipart/form-data">
    <h3>Delivery Charges</h3>
    <select id="dest1" name="dest1" class="box" onchange="delivery();">
        <option value="beirut">Beirut</option>
        <option value="mount lebanon">Mount Lebanon</option>
        <option value="north lebanon">North Lebanon</option>
        <option value="akkar">Akkar</option>
        <option value="baalbek">Baalbek</option>
        <option value="beqaa">Beqaa</option>
        <option value="south lebanon">South Lebanon</option>
        <option value="Nabatieh">Nabatieh</option>
    </select>

    <select id="dest2" name="dest2" class="box" onchange="delivery();">
        <option value="beirut">Beirut</option>
        <option value="mount lebanon">Mount Lebanon</option>
        <option value="north lebanon">North Lebanon</option>
        <option value="akkar">Akkar</option>
        <option value="baalbek">Baalbek</option>
        <option value="beqaa">Beqaa</option>
        <option value="south lebanon">South Lebanon</option>
        <option value="Nabatieh">Nabatieh</option>
    </select>
   <input type="text" id="charge" value ="0 L.L." name="charge" class="box" required>
   <input type="submit" value="update" name="update" class="btn">
</form>

</section>

</div>

<!-- custom js file link  -->
<script src="js/script_admin.js"></script>

</body>
<?php } ?>
</html>
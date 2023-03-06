<?php require_once "controllerUserData.php"; 
if(!isset($_SESSION['logged_in']) && $_SESSION['logged_in']!=true){
   setcookie('why',"Please Login First.");
   header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    
    <link rel="shortcut icon" type="image/png" href="/project/image/logo.png">

</head>

<style>
.checkout{
    padding-top: 17rem;
    background:rgba(255, 255, 255, 0.9);
    }

.shopping-cart{
    padding-left: 250px;
    padding-right: 30px;
}

.shopping-cart table{
   text-align: center;
   width: 100%;
}

.shopping-cart table thead th{
   padding:1.5rem;
   font-size: 2rem;
   color:var(--white);
   background-color: var(--black);
}

.shopping-cart table tr td{
   border-bottom: var(--border);
   padding:1.5rem;
   font-size: 2rem;
   color:var(--black);
}

.shopping-cart table input[type="number"]{
   border: var(--border);
   padding:1rem 2rem;
   font-size: 2rem;
   color:var(--black);
   width: 10rem;
}

.shopping-cart table input[type="submit"]{
   padding:.5rem 1.5rem;
   cursor: pointer;
   font-size: 2rem;
   background-color: var(--orange);
   color:var(--white);
}

.shopping-cart table input[type="submit"]:hover{
   background-color: var(--black);
}

.shopping-cart table .table-bottom{
   background-color: #eee;
}

.shopping-cart .checkout-btn{
   text-align: center;
   margin-top: 1rem;
}

.shopping-cart .checkout-btn a{
   display: inline-block;
   width: auto;
}

.shopping-cart .checkout-btn a.disabled{
   pointer-events: none;
   opacity: .5;
   user-select: none;
   background-color: var(--orange);
}

@media (max-width:700px){

.shopping-cart{
   overflow-x: scroll;
   padding-left: 30px;
    padding-right: 30px;
}

.shopping-cart table{
   width: 120rem;
}

.shopping-cart .heading{
   text-align: left;
}

.shopping-cart .checkout-btn{
   text-align: left;
}

}
</style>

<?php
if(!isset($_SESSION['logged_in']) && $_SESSION['logged_in']!=true){
    header('Location:login.php');
}

if(isset($_POST['update_update_btn'])){
    $update_value = $_POST['update_quantity'];
    $update_id = $_POST['update_quantity_id'];
    if(!$update_value){
        $update_value=1;
    }
    $user_id = $_SESSION['id'];
    $update_quantity_query = mysqli_query($link, "UPDATE carts SET quantity = '$update_value' WHERE user_id = '$user_id' AND combination_id = '$update_id'");
    if($update_quantity_query){
       header('location:checkout.php');
    };
 };
 
 if(isset($_GET['remove'])){
    $remove_id = $_GET['remove'];
    $user_id = $_SESSION['id'];
    mysqli_query($link, "DELETE FROM carts WHERE user_id = '$user_id' AND combination_id = '$remove_id'");
    header('location:checkout.php');
 };
 
 if(isset($_GET['delete_all'])){
    $user_id = $_SESSION['id'];
    mysqli_query($link, "DELETE FROM carts WHERE user_id = '$user_id'");
    header('location:checkout.php');
 }
?>
<body id="product">
<header class="header">

    <a href="index.php" class="logo"> <i class="fas fa-shopping-basket"></i> Waffer </a>

    <nav class="navbar">
        <a href="index.php#home">home</a>
        <a href="index.php#markets">markets</a>
        <a href="index.php#products">products</a>
        <a href="index.php#categories">categories</a>
        <a href="index.php#review">review</a>
        <a href="index.php#blogs">blogs</a>
    </nav>
    
    <div class="icons">
        <div class="fas fa-filter" id="cat-btn"></div>
        <div class="fas fa-bars" id="menu-btn"></div>
        <div class="fas fa-search" id="search-btn"></div>
        <div class="fas fa-user" id="login-btn"></div>
    </div>
    <form action="" class="search-form">
        <div class="srch">
        <input type="search" id="search-box" placeholder="search here..." onkeyup="showResult(this.value)">
        <label for="search-box" class="fas fa-search"></label>
        </div>
        <div id="livesearch"></div>
    </form>

    <?php
     if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true){
        if(isset($_POST['location'])){
            $location = $_SESSION['location']=$_POST['location'];
            }else{
                $location = $_SESSION['location'];
            }
        $user_id = $_SESSION['id'];
        $email=$_SESSION['email'];
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($link,$query);
        $row= mysqli_fetch_assoc($result);
        $name = $row['name'];
        $address = $row['address'];
        echo '<form action="logout.php" class="login-form">
        <h3>'.$name.'</h3>
        <h4>'.$email.'</h4>
        <h4>Address: '.$address.'</h4>
        <h4>Delivering to: '.$location.'</h4><br/>
        <a href="welcome.php">Click here to change delivery location</a><br/><br/>
        <a href="change-password.php">Change Password</a><br/>
        <input type="submit" value="log out" class="btn">
        </form>';
     }else{
        echo '<form action="login.php" method="POST" autocomplete="" class="login-form">
        <h3>login now</h3>
        <input type="email" placeholder="your email" name="email" class="box" required>
        <input type="password" placeholder="your password" name="password" class="box" required>
        <p>forgot your password? <a href="forgot-password.php">click here</a></p>
        <p>don\'t have an account> <a href="register.php">create now</a></p>
        <input type="submit" value="login now" name="login" class="btn">
        </form>';
    }
      $page = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
      setcookie('last_page',$page);
?>
</header>

<div class="sidebar">
    <h1 style="padding-left: 2rem;background-color: #ff7800;;padding: 12px;">Categories</h1><br/>
<?php
    require_once('connection.php');
    $query="SELECT * FROM categories;";
    $result=mysqli_query($link,$query);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            echo '<a href="category.php?cid='.$row['category_id'].'">'.$row['category_name'].'</a>';
        }
    }
?>
</div>
<section class="checkout">
<section class="shopping-cart">

<h1 class="heading">shopping cart</h1>

<table>

   <thead>
      <th>image</th>
      <th>name</th>
      <th>market</th>
      <th>price</th>
      <th>quantity</th>
      <th style="width:20%">total price</th>
      <th>action</th>
   </thead>

   <tbody>

      <?php 
      
      $select_cart = mysqli_query($link, "SELECT * FROM ((((carts INNER JOIN markets_products ON carts.combination_id = markets_products.combination_id)
      INNER JOIN products ON markets_products.product_id = products.product_id)INNER JOIN images ON products.image_id = images.image_id)INNER JOIN markets ON markets_products.market_id = markets.market_id) WHERE user_id='$user_id'");
      $grand_total = 0;
      $markets_loc = array();
      $charge=0;
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
        $imageURL = 'image/products/'.substr($fetch_cart['file_name'],8);
      ?>

      <tr>
         <td><img src="<?=$imageURL?>" height="100" alt="<?=$fetch_cart['product_name']?>"></td>
         <td><?php echo $fetch_cart['product_name']; ?></td>
         <td><?php echo $fetch_cart['market_name'].' - '.$fetch_cart['location'] ?></td>
         <td><?php echo number_format($fetch_cart['price']); ?> L.L.</td>
         <td>
            <form action="" method="post">
               <input type="hidden" name="update_quantity_id"  value="<?php echo $fetch_cart['combination_id']; ?>" >
               <input type="number" name="update_quantity" min="1" max="5"  value="<?php echo $fetch_cart['quantity']; ?>" >
               <input type="submit" value="update" name="update_update_btn">
            </form>   
         </td>
         <td><?php
         $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
          echo number_format($sub_total); ?> L.L.</td>
         <td><a href="checkout.php?remove=<?php echo $fetch_cart['combination_id']; ?>" onclick="return confirm('remove item from cart?')" class="delete-btn"> <i class="fas fa-trash"></i> remove</a></td>
      </tr>
      <?php
        $grand_total += $sub_total;
         $markets_loc[] = $fetch_cart['location'];
        $markets_loc = array_unique($markets_loc);
         };
         $query ="SELECT * FROM delivery WHERE dest1='$location' OR dest2='$location'";
         $result = mysqli_query($link,$query);
         while($row = mysqli_fetch_assoc($result)){
            for($i=0;$i<count($markets_loc);$i++){
               if($markets_loc[$i]==$row['dest1'] && $location==$row['dest2']){
                  $charge+=$row['charge'];
               }elseif ($markets_loc[$i]==$row['dest2'] && $location==$row['dest1']) {
                  $charge+=$row['charge'];
               }
            }  
      };
      $grand_total += $charge;
   }
      ?>
      <tr class="table-bottom">
         <td colspan="5">Delivery Charge</td>
         <td><?php echo number_format($charge); ?> L.L.</td>
         <td></td>
      </tr>
      <tr class="table-bottom">
         <td><a href="index.php" class="option-btn" style="margin-top: 0;">continue shopping</a></td>
         <td colspan="4">grand total</td>
         <td><?php echo number_format($grand_total); ?> L.L.</td>
         <td><a href="checkout.php?delete_all" onclick="return confirm('are you sure you want to delete all?');" class="delete-btn"> <i class="fas fa-trash"></i> delete all </a></td>
      </tr>

   </tbody>

</table>

<div class="checkout-btn">
   <a href="" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Place Order</a>
</div>

</section>
</section>

<section class="footer" id="footer">

    <div class="box-container">

        <div class="box">
            <h3> Waffer <i class="fas fa-shopping-basket"></i> </h3>
            <p>The information is provided by WAFFER and while we endeavour to keep 
                the information up to date and correct, we make no representations or warranties of any kind.</p>
            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-linkedin"></a>
            </div>
        </div>

        <div class="box">
            <h3>contact info</h3>
            <a href="#" class="links"> <i class="fas fa-phone"></i> +961 X XXX XXX </a>
            <a href="#" class="links"> <i class="fas fa-phone"></i> +961 X XXX XXX </a>
            <a href="#" class="links"> <i class="fas fa-envelope"></i> info@waffer.com </a>
            <a href="#" class="links"> <i class="fas fa-map-marker-alt"></i> Beirut, Lebanon </a>
        </div>

        <div class="box">
            <h3>quick links</h3>
            <a href="index.php#home" class="links"> <i class="fas fa-arrow-right"></i> home </a>
            <a href="index.php#markets" class="links"> <i class="fas fa-arrow-right"></i> markets </a>
            <a href="index.php#products" class="links"> <i class="fas fa-arrow-right"></i> products of the day</a>
            <a href="index.php#categories" class="links"> <i class="fas fa-arrow-right"></i> categories </a>
            <a href="index.php#review" class="links"> <i class="fas fa-arrow-right"></i> review </a>
            <a href="index.php#blogs" class="links"> <i class="fas fa-arrow-right"></i> blogs </a>
        </div>

        <div class="box">
            <h3>newsletter</h3>
            <p>subscribe for latest updates</p>
            <input type="email" placeholder="your email" class="email">
            <input type="submit" value="subscribe" class="btn">
            <img src="image/payment.png" class="payment-img" alt="credit card">
        </div>

    </div>

    <div class="credit"> created by <span> Joe EID and Fatima El Kheshen </span> | all rights reserved </div>

</section>

<script src="js/script_cart.js"></script>
</body>
</html>
<?php
mysqli_close($link);
?>
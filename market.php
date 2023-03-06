<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The markets</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

    <link rel="shortcut icon" type="image/png" href="/project/image/logo.png">

</head>

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
        <div class="fa-stack fa-2x has-badge" data-count="0" id="cart-btn">
        <i class="fa fa-circle fa-stack-2x fa-inverse"></i>
        <i class="fa fa-shopping-cart fa-stack-2x red-cart"></i>
        </div>
        <div class="fas fa-user" id="login-btn"></div>
    </div>
    <form action="search.php" method="GET" class="search-form">
        <div class="srch">
        <input type="search" id="search-box" name="q" placeholder="search here..." onkeyup="showResult(this.value)">
        <label for="search-box" class="fas fa-search"></label>
        </div>
        <div id="livesearch"></div>
    </form>

    <div class="shopping-cart" id="shop">
    </div>

    <?php
     if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true){
        if(isset($_POST['location'])){
            $location = $_SESSION['location']=$_POST['location'];
            }else{
                $location = $_SESSION['location'];
            }
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
    <h1 style="padding-left: 2rem;background-color: #ff7800;;padding: 12px;">Markets</h1><br/>
<?php
if(isset($_GET['mid'])){
    $mid = $_GET['mid'];
    require_once('connection.php');
    $query="SELECT * FROM markets;";
    $result=mysqli_query($link,$query);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $class='';
             if($row['market_id']==$mid){
                 $class='class="active"';
             }
            echo '<a '.$class.'" href="market.php?mid='.$row['market_id'].'">'.$row['market_name'].'</a>';
        }
    }
?>
</div>
<section class="products-category">
<div class="box-container" id="content">
    <?php
    $query = "SELECT * FROM ((images INNER JOIN products ON images.image_id = products.image_id) INNER JOIN markets_products ON products.product_id = markets_products.product_id) WHERE markets_products.market_id=$mid;";
    $result = mysqli_query($link, $query);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $imageURL = 'image/products/'.substr($row['file_name'],8);
            echo '<div class="box">';
            echo '<img src="'.$imageURL.'" alt="'.$row['product_name'].' image">
            <h3>'.$row['product_name'].'</h3>
            <p>'.$row['description'].'</p>
            <a href="productdetails.php?pid='.$row['product_id'].'" class="btn">Show options</a>
            </div>';
        }
    }
    echo '<h3>More Items coming soon...</h3>';
}
    ?>
    </div>
</section>

</div>
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

<script src="js/script_product.js"></script>
</body>
</html>
<?php
mysqli_close($link);
?>
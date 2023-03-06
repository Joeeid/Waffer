<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WAFFER</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

    <link rel="shortcut icon" type="image/png" href="/project/image/logo.png">

</head>
<body>
    
<!-- header section starts  -->

<header class="header">

    <a href="#home" class="logo"> <i class="fas fa-shopping-basket"></i> Waffer </a>

    <nav class="navbar">
        <a href="#home">home</a>
        <a href="#markets">markets</a>
        <a href="#products">products</a>
        <a href="#categories">categories</a>
        <a href="#review">review</a>
        <a href="#blogs">blogs</a>
    </nav>

    <div class="icons">
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
        $number = $row['number'];
        echo '<form action="logout.php" class="login-form">
        <h3>'.$name.'</h3>
        <h4>'.$number.'</h4>
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
    setcookie('last_page',"index.php");
?>
    
</header>

<!-- header section ends -->

<!-- home section starts  -->

<section class="home" id="home">

    <div class="content">
        <?php
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true){
        echo '<h2>'.$name.'</h2>';
        }
        ?>
        <h3>What's the <span>best option</span> for you?</h3>
        <p>Waffer helps you to choose the best value-for-money product. Don't believe me? Try it out!</p>
        <a href="waffer.php" class="btn">Let's WAFFER</a>
    </div>

</section>

<!-- home section ends -->

<!-- features section starts  -->

<section class="markets" id="markets">

    <h1 class="heading"> the <span>markets</span> </h1>

    <div class="swiper market-slider">

        <div class="swiper-wrapper">
        <?php
        $query = "SELECT * FROM (images INNER JOIN markets ON images.image_id = markets.image_id) WHERE file_name LIKE 'market_%'";
        $result = mysqli_query($link, $query);
        if($result){
            while($row = mysqli_fetch_assoc($result)){
                $imageURL = 'image/markets/'.substr($row['file_name'],7);
                echo '<div class="swiper-slide box">';
                echo '<img src="'.$imageURL.'" alt="'.$row['market_name'].' Logo">';
                echo '<h3><a href="'.$row['website'].'" target="_blank">'.$row['market_name'].'</a></h3>';
                echo'<p style="font-size: 1.5rem;">'.$row['location'].'</p>';
                echo '<p style="color:#ff7800;">'.$row['slogan'].'</p>';
                echo '<a href="market.php?mid='.$row['market_id'].'" class="btn">Shop Now</a>';
                echo '</div>';
            }
        }else{
            echo '<div class="content">';
            echo '<h3>Markets coming <span>soon</span>...</h3>';
            echo '</div>';
        }
        ?>
        </div>
    </div>

</section>

<!-- features section ends -->

<!-- products section starts  -->

<section class="products" id="products">

    <h1 class="heading"><span>products</span> of the day </h1>

    <div class="swiper product-slider">

        <div class="swiper-wrapper">

            <div class="swiper-slide box">
                <img src="image/product-1.png" alt="">
                <h3>fresh orange</h3>
                <div class="price">20,000 L.L.</div>
                <a href="#" class="btn">add to cart</a>
            </div>

            <div class="swiper-slide box">
                <img src="image/product-2.png" alt="">
                <h3>fresh onion</h3>
                <div class="price">15,000 L.L.</div>
                <a href="#" class="btn">add to cart</a>
            </div>

            <div class="swiper-slide box">
                <img src="image/product-5.png" alt="">
                <h3>fresh potato</h3>
                <div class="price">27,000 L.L.</div>
                <a href="#" class="btn">add to cart</a>
            </div>

            <div class="swiper-slide box">
                <img src="image/product-7.png" alt="">
                <h3>fresh carrot</h3>
                <div class="price">20,000 L.L.</div>
                <a href="#" class="btn">add to cart</a>
            </div>

        </div>

    </div>

</section>

<!-- products section ends -->

<!-- categories section starts  -->

<section class="categories" id="categories">

    <h1 class="heading"> product <span>categories</span> </h1>

    <div class="box-container">
    <?php
    $query = "SELECT * FROM (images INNER JOIN categories ON images.image_id = categories.image_id) WHERE file_name LIKE 'category_%';";
    $result = mysqli_query($link, $query);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $imageURL = 'image/categories/'.substr($row['file_name'],9);
            echo '<div class="box">';
            echo '<img src="'.$imageURL.'" alt="'.$row['category_name'].' image">
            <h3>'.$row['category_name'].'</h3>
            <p>up to 15% off</p>
            <a href="category.php?cid='.$row['category_id'].'" class="btn">shop now</a>
            </div>';
        }
    }
    ?>
    </div>

</section>

<!-- categories section ends -->

<!-- review section starts  -->

<section class="review" id="review">

    <h1 class="heading"> customer's <span>review</span> </h1>

    <div class="swiper review-slider">

        <div class="swiper-wrapper">

            <div class="swiper-slide box">
                <img src="image/pic-1.png" alt="">
                <p>I like the website very much since it is so straight and very powerful.</p>
                <h3>john deo</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

            <div class="swiper-slide box">
                <img src="image/pic-2.png" alt="">
                <p>Great website to save tons of money. Thanks!</p>
                <h3>Meera James</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
            </div>

            <div class="swiper-slide box">
                <img src="image/pic-3.png" alt="">
                <p>One word: "Waffer"!</p>
                <h3>Steph Harry</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>

        </div>

    </div>

</section>

<!-- review section ends -->

<!-- blogs section starts  -->

<section class="blogs" id="blogs">

    <h1 class="heading"> our <span>blogs</span> </h1>

    <div class="box-container">

        <div class="box">
            <img src="image/vegetables.jpg" alt=" Vegetables Image">
            <div class="content">
                <div class="icons">
                    <a href="#"> <i class="fas fa-user"></i> by user </a>
                    <a href="#"> <i class="fas fa-calendar"></i> 5th jan, 2023 </a>
                </div>
                <h3>10 things you didn't know about fresh vegetables.</h3>
                <a href="#" class="btn">read more</a>
            </div>
        </div>

        <div class="box">
            <img src="image/cacao.webp" alt="Cacao Image">
            <div class="content">
                <div class="icons">
                    <a href="#"> <i class="fas fa-user"></i> by user </a>
                    <a href="#"> <i class="fas fa-calendar"></i> 1st dec, 2022 </a>
                </div>
                <h3>From cacao beens to chocolate bar.</h3>
                <a href="#" class="btn">read more</a>
            </div>
        </div>

        <div class="box">
            <img src="image/diet.jpg" alt="Diet Image">
            <div class="content">
                <div class="icons">
                    <a href="#"> <i class="fas fa-user"></i> by user </a>
                    <a href="#"> <i class="fas fa-calendar"></i> 23 sep, 2022 </a>
                </div>
                <h3>Light, fat free and diet. What's the difference?</h3>
                <a href="#" class="btn">read more</a>
            </div>
        </div>

    </div>

</section>

<!-- blogs section ends -->

<!-- footer section starts  -->

<section class="footer">

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
            <br/><br/>
            <a style="font-size: 2rem; color:#ff7800; font-weight:bold" href="seller-login.php">Login as a Seller</a>
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
            <a href="#home" class="links"> <i class="fas fa-arrow-right"></i> home </a>
            <a href="#markets" class="links"> <i class="fas fa-arrow-right"></i> markets </a>
            <a href="#products" class="links"> <i class="fas fa-arrow-right"></i> products of the day</a>
            <a href="#categories" class="links"> <i class="fas fa-arrow-right"></i> categories </a>
            <a href="#review" class="links"> <i class="fas fa-arrow-right"></i> review </a>
            <a href="#blogs" class="links"> <i class="fas fa-arrow-right"></i> blogs </a>
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

<!-- footer section ends -->

<?php
mysqli_close($link);
?>













<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
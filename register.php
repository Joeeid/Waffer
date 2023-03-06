<?php require_once "controllerUserData.php"; 
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){
    header('Location: index.php');
}else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register to Waffer</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

    <link rel="shortcut icon" type="image/png" href="/project/image/logo.png">

</head>
<script>
    function checkpass(){
        let check = new RegExp('(?=.*[A-Z])(?=.*[0-9])(?=.{8,})')
        password = document.getElementById("pass").value;
        if(password.length==0){
            document.getElementById("message").innerHTML = "";
        }else if(password.length<8){
            document.getElementById("message").innerHTML = "Password too short!";
        }else if(!check.test(password)){
            document.getElementById("message").innerHTML = "Password too weak!";
        }else{
            document.getElementById("message").innerHTML = "";
            return true;
        }
        return false;
    }
    function confirmpass(){
        password = document.getElementById("pass").value;
        password2 = document.getElementById("pass2").value;
        if(password2.length==0){
            document.getElementById("message2").innerHTML = "";
        }
        else if(password!=password2){
            document.getElementById("message2").innerHTML = "Passwords do not match!";
        }else{
            document.getElementById("message2").innerHTML = "";
            return true;
        }
        return false;
    }
    function validateForm(){
        if(checkpass()==true && confirmpass()==true){
            return true;
        }else{
            document.getElementById("invalid").className = "alert alert-warning";
            document.getElementById("invalid").innerHTML = "Take another look at the passwords!";
            return false;
        }
    }
</script>
<body id="register">
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
        <div class="fas fa-bars" id="menu-btn"></div>
    </div>
</header>
    <section class="register">

    <div class="content">
        <form action="register.php" method="POST" autocomplete="" class="login-form" onsubmit="return validateForm();">
        <h3><span>Register now</span></h3>
        <p>Please Enter your informations:</p>
                <?php
                    if(count($errors) == 1){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }elseif(count($errors) > 1){
                        ?>
                        <div class="alert alert-danger">
                            <?php
                            foreach($errors as $showerror){
                                ?>
                                <li><?php echo $showerror; ?></li>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
        <input type="text" placeholder="Name" name="name" class="box" required><br/>
        <input type="email" placeholder="Email" name="email" class="box" required><br/>
        <input type="password" placeholder="Password" id="pass" name="password" class="box" onkeyup="checkpass()" required><br/>
        <div class="alert alert-info" id="invalid" style="font-size:1.5rem;">Password needs to have at least 1 upper character, 1 lower character and 1 number.</div>
        <span id="message" style="color:red;font-size: 1.5rem;"></span>
        <input type="password" placeholder="ReEnter Password" id="pass2" name="cpassword" class="box" onkeyup="confirmpass()" required><br/>
        <span id="message2" style="color:red;font-size: 1.5rem;"></span>
        <input type="text" placeholder="Your Full Address" name="address" class="box" required><br/>
        <input type="submit" value="Register Now" name="signup" class="btn">
        <p>Already a member? <a href="login.php">Log in</a></p>
        </form>
    </div>

    </section>

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
    <script src="js/script_reg.js"></script>

</body>
</html>
<?php
}
mysqli_close($link);
?>
<?php require_once "controllerUserData.php"; ?>
<?php
if(isset($_SESSION['logged_in_admin']) && $_SESSION['logged_in_admin'] == true){
    header('Location: admin.php');
}else{
if(isset($_POST['loginadmin'])){
	$email = mysqli_real_escape_string($link, $_POST['email']);
	$password = mysqli_real_escape_string($link, $_POST['password']);
	$check_email = "SELECT * FROM admins WHERE email = '$email'";
	$res = mysqli_query($link, $check_email);
	if(mysqli_num_rows($res) > 0){
		$fetch = mysqli_fetch_assoc($res);
		$fetch_pass = $fetch['password'];
		if(md5($password)==$fetch_pass){
			    $_SESSION['admin-email'] = $email;
				$name = $fetch['name'];
				$id = $fetch['admin_id'];
				$_SESSION['info']=null;
				$_SESSION['admin-id'] = $id;
			  	$_SESSION['admin-name'] = $name;
			  	$_SESSION['logged_in_admin']=true;
				header('location: admin.php');
		}else{
			$errors['email'] = "Incorrect email or password!";
		}
	}else{
		$errors['email'] = "It's look like you're not an admin!";
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Waffer</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

    <link rel="shortcut icon" type="image/png" href="/project/image/logo.png">

</head>
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
                <form action="admin-login.php" method="POST" autocomplete="" class="login-form">
                    <h3><span>Admin Login Form</span></h3>
                    <p>Login with your email and password.</p>
                    <?php
                    if(count($errors) > 0){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                        <input class="box" type="email" name="email" placeholder="Email Address" required value="<?php echo $email ?>">
                        <input class="box" type="password" name="password" placeholder="Password" required>
                        <input class="btn" type="submit" name="loginadmin" value="Login"><br/>
                        <p>
                    <!-- <a style="font-size: 1.8rem;" href="forgot-password.php">Forgot password?</a><br/> -->
                        Not an admin? <a href="login.php">Login as user</a></p>
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
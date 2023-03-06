<?php session_start();
require('connection.php');
if(!isset($_SESSION['logged_in_seller']) && $_SESSION['logged_in_seller'] !=true){
    header('Location: seller-login.php');
}else{

    //if change password click
if(isset($_POST['password-change'])){
	$email = $_SESSION['seller-email'];
	$opassword = mysqli_real_escape_string($link, $_POST['opassword']);
	$password = mysqli_real_escape_string($link, $_POST['password']);
	$cpassword = mysqli_real_escape_string($link, $_POST['cpassword']);
	$check_email = "SELECT * FROM sellers WHERE email = '$email'";
	$res = mysqli_query($link, $check_email);
	if(mysqli_num_rows($res) > 0){
		$fetch = mysqli_fetch_assoc($res);
		$fetch_pass = $fetch['password'];
		if(md5($opassword)==$fetch_pass){
			if($password !== $cpassword){
				$errors['password'] = "Confirm password not matched!";
			}else{
				if($opassword !==$password){
					$email = $_SESSION['seller-email']; //getting this email using session
					$encpass = md5($password);
					$update_pass = "UPDATE sellers SET password = '$encpass' WHERE email = '$email'";
					$run_query = mysqli_query($link, $update_pass);
					if($run_query){
						$_SESSION['info']=null;
						header('Location: seller.php');
					}else{
						$errors['db-error'] = "Failed to change your password!";
					}
				}else{
					$errors['password'] = "Your new password can't be the current one.";
				}
			}
		}else{
			$errors['password'] = "Current password is not correct. Please check it again.";
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
   <title>Waffer Seller</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/seller-style.css">

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
<body>
<header class="header">

   <div class="flex">

      <a href="seller.php" class="logo"><i class="fas fa-shopping-basket"></i> Waffer</a>
      <h2>Welcome <?=$_SESSION['seller-name']?></h2>
      <nav class="navbar">
         <a href="seller.php">Go back</a>
         <a href="addcategory.php">categories</a>
         <a href="addproduct.php">products</a>
         <a href="logout.php">Logout</a>
      </nav>

      <div id="menu-btn" class="fas fa-bars"></div>

   </div>

</header>

<div class="container">
    <section>
        <form action="password-seller.php" method="POST" autocomplete="off" class="add-product-form" onsubmit="return validateForm();">
                    <h3><span>New Password</span></h3>
                    <?php
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }

                    if(isset($errors)){
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
                }
                    ?>
                        <input class="box" type="password" id="oldpass" name="opassword" placeholder="Enter current password" required>
                        <div class="alert alert-info" id="invalid" style="font-size:1.5rem;">Password needs to have at least 1 upper character, 1 lower character and 1 number.</div>
                        <input class="box" type="password" id="pass" name="password" placeholder="Create new password" onkeyup="checkpass()" required>
                        <span id="message" style="color:red;font-size: 1.5rem;"></span>
                        <input class="box" type="password" id="pass2" name="cpassword" placeholder="Confirm your password" onkeyup="confirmpass()" required>
                        <span id="message2" style="color:red;font-size: 1.5rem;"></span><br/>
                        <input class="btn" type="submit" name="password-change" value="Change">
                </form>
        </section>
    </div>
    


    <script src="js/script_seller.js"></script>

</body>
</html>
<?php
}
mysqli_close($link);
?>
<?php
session_start();
require('connection.php');
$email = "";
$name = "";
$errors = array();

//register now
    if(isset($_POST['signup'])){
	$name = mysqli_real_escape_string($link, $_POST['name']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $password = mysqli_real_escape_string($link, $_POST['password']);
    $cpassword = mysqli_real_escape_string($link, $_POST['cpassword']);
	$address = mysqli_real_escape_string($link, $_POST['address']);
    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
    }
	$email_check = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email that you have entered already exists!";
    }
	if(count($errors) === 0){
	$encpass = md5($password);
	$code = rand(999999, 111111);
    $status = "notverified";
	$query = "INSERT INTO users (user_id,name,email,password,address,code,status) 
	VALUES(NULL,'$name','$email','$encpass','$address','$code','$status')";
	$result = mysqli_query($link, $query);
	if(!$result) {
		$errors['db-error'] = "Failed while inserting data into database!";
	}else{
		$subject = "Email Verification Code";
            $message = "Your verification code is $code";
            $sender = "From: waffer.lb@gmail.com";
            if(mail($email, $subject, $message, $sender)){
                $info = "We've sent a verification code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: user-otp.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while sending code!";
            }
		}
	}
}

//if user click verification code submit button
if(isset($_POST['check'])){
	$_SESSION['info'] = "";
	$otp_code = mysqli_real_escape_string($link, $_POST['otp']);
	$check_code = "SELECT * FROM users WHERE code = $otp_code";
	$code_res = mysqli_query($link, $check_code);
	if(mysqli_num_rows($code_res) > 0){
		$fetch_data = mysqli_fetch_assoc($code_res);
		$fetch_code = $fetch_data['code'];
		$email = $fetch_data['email'];
		$code = 0;
		$status = 'verified';
		$update_otp = "UPDATE users SET code = $code, status = '$status' WHERE code = $fetch_code";
		$update_res = mysqli_query($link, $update_otp);
		if($update_res){
			$id = $fetch_data['user_id'];
			$_SESSION['id'] = $id;
			$name = $fetch_data['name'];
			$_SESSION['name'] = $name;
			$_SESSION['email'] = $email;
			$_SESSION['logged_in']=true;
			header('location: welcome.php');
			exit();
		}else{
			$errors['otp-error'] = "Failed while updating code!";
		}
	}else{
		$errors['otp-error'] = "You've entered incorrect code!";
	}
}

//if user click login button
if(isset($_POST['login'])){
	$email = mysqli_real_escape_string($link, $_POST['email']);
	$password = mysqli_real_escape_string($link, $_POST['password']);
	$check_email = "SELECT * FROM users WHERE email = '$email'";
	$res = mysqli_query($link, $check_email);
	if(mysqli_num_rows($res) > 0){
		$fetch = mysqli_fetch_assoc($res);
		$fetch_pass = $fetch['password'];
		if(md5($password)==$fetch_pass){
			$_SESSION['email'] = $email;
			$status = $fetch['status'];
			if($status == 'verified'){
				$name = $fetch['name'];
				$id = $fetch['user_id'];
				$_SESSION['info']=null;
				$_SESSION['id'] = $id;
			  	$_SESSION['email'] = $email;
			  	$_SESSION['name'] = $name;
			  	$_SESSION['logged_in']=true;
				header('location: welcome.php');
			}else{
				$info = "It's look like you haven't still verify your email - $email";
				$_SESSION['info'] = $info;
				header('location: user-otp.php');
			}
		}else{
			$errors['email'] = "Incorrect email or password!";
		}
	}else{
		$errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
	}
}

//if user click continue button in forgot password form
if(isset($_POST['check-email'])){
	$email = mysqli_real_escape_string($link, $_POST['email']);
	$check_email = "SELECT * FROM users WHERE email='$email'";
	$run_sql = mysqli_query($link, $check_email);
	if(mysqli_num_rows($run_sql) > 0){
		$code = rand(999999, 111111);
		$insert_code = "UPDATE users SET code = $code WHERE email = '$email'";
		$run_query =  mysqli_query($link, $insert_code);
		if($run_query){
			$subject = "Password Reset Code";
			$message = "Your password reset code is $code";
			$sender = "From: waffer.lb@gmail.com";
			if(mail($email, $subject, $message, $sender)){
				$info = "We've sent a password reset otp to your email - $email";
				$_SESSION['info'] = $info;
				$_SESSION['email'] = $email;
				header('location: reset-code.php');
				exit();
			}else{
				$errors['otp-error'] = "Failed while sending code!";
			}
		}else{
			$errors['db-error'] = "Something went wrong!";
		}
	}else{
		$errors['email'] = "This email address does not exist!";
	}
}

//if user click check reset otp button
if(isset($_POST['check-reset-otp'])){
	$_SESSION['info'] = "";
	$otp_code = mysqli_real_escape_string($link, $_POST['otp']);
	$check_code = "SELECT * FROM users WHERE code = $otp_code";
	$code_res = mysqli_query($link, $check_code);
	if(mysqli_num_rows($code_res) > 0){
		$fetch_data = mysqli_fetch_assoc($code_res);
		$email = $fetch_data['email'];
		$_SESSION['email'] = $email;
		$info = "Please create a new password that you don't use on any other site.";
		$_SESSION['info'] = $info;
		header('location: new-password.php');
		exit();
	}else{
		$errors['otp-error'] = "You've entered incorrect code!";
	}
}

//if user click change password button
if(isset($_POST['change-password'])){
	$_SESSION['info'] = "";
	$password = mysqli_real_escape_string($link, $_POST['password']);
	$cpassword = mysqli_real_escape_string($link, $_POST['cpassword']);
	if($password !== $cpassword){
		$errors['password'] = "Confirm password not matched!";
	}else{
		$code = 0;
		$email = $_SESSION['email']; //getting this email using session
		$encpass = md5($password);
		$update_pass = "UPDATE users SET code = $code, password = '$encpass' WHERE email = '$email'";
		$run_query = mysqli_query($link, $update_pass);
		if($run_query){
			$info = "Your password changed. Now you can login with your new password.";
			$_SESSION['info'] = $info;
			header('Location: password-changed.php');
		}else{
			$errors['db-error'] = "Failed to change your password!";
		}
	}
}

//if login now button click
if(isset($_POST['login-now'])){
	header('Location: login.php');
}

//if change password click
if(isset($_POST['password-change'])){
	$email = $_SESSION['email'];
	$opassword = mysqli_real_escape_string($link, $_POST['opassword']);
	$password = mysqli_real_escape_string($link, $_POST['password']);
	$cpassword = mysqli_real_escape_string($link, $_POST['cpassword']);
	$check_email = "SELECT * FROM users WHERE email = '$email'";
	$res = mysqli_query($link, $check_email);
	if(mysqli_num_rows($res) > 0){
		$fetch = mysqli_fetch_assoc($res);
		$fetch_pass = $fetch['password'];
		if(md5($opassword)==$fetch_pass){
			if($password !== $cpassword){
				$errors['password'] = "Confirm password not matched!";
			}else{
				if($opassword !==$password){
					$code = 0;
					$email = $_SESSION['email']; //getting this email using session
					$encpass = md5($password);
					$update_pass = "UPDATE users SET code = $code, password = '$encpass' WHERE email = '$email'";
					$run_query = mysqli_query($link, $update_pass);
					if($run_query){
						$_SESSION['info']=null;
						header('Location: index.php');
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
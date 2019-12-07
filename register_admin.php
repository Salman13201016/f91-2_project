<?php
	include 'database/connect.php';
	/* PHP MAILER LIBRARY */
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require 'phpmailer/src/Exception.php';
	require 'phpmailer/src/PHPMailer.php';
	require 'phpmailer/src/SMTP.php';
	$error ='';
	$length ='';
	$pwd='';
	$email_check='';
	if(isset($_POST['signup'])){
		$email = mysqli_escape_string($conn,$_POST['email']);
		$password = mysqli_escape_string($conn,$_POST['password']);
		$confirmPassword = mysqli_escape_string($conn,$_POST['confirmpassword']);

		$sql = "SELECT email FROM admin WHERE email = '$email'";
		$query = mysqli_query($conn,$sql);
		if(mysqli_num_rows($query)>0){
			$email_check="You email is already existed";
		}
		elseif(strlen($password)<6){
			$length ="Password length must be greater than 5";
		}
		elseif(empty($email) || empty($password) || empty($confirmPassword)){
			$error = "This field is required";
		}
		elseif($password!=$confirmPassword){
			$pwd='Your password are not equal';
		}
		else{
			/* Encrypted password */
			$password = md5($password);
			/* Generate a new verification key with encryption */
			$vkey = md5(time().$email);

			/* Insert Data into user table */
			$sql = "INSERT INTO admin (email,pass,v_key,v_status) VALUES ('$email','$password','$vkey',0)";
			$query = mysqli_query($conn,$sql);

			if($query){
				$mail = new PHPMailer;
				//* set phpmailer to use SMTP */
				$mail->isSMTP();
				/* smtp host */
				$mail->Host = "smtp.gmail.com";
				$mail->SMTPAuth = true;
				
				/* Provide User Name and Password as your email address(FromEmail) */
				$mail->Username = "your email address (from email)";
				$mail->Password = "your password (from email)";
				$mail->SMTPSecure ="tls";
				$mail->Port= 587;
				$mail->From = "your email address (from email)";
				$mail->FromName = "sms-f191";
				$mail->addAddress($email,"Salman");
				$mail->isHTML(true);
				/* Set Subject and messages of body */
				$mail->Subject = "Email Verification From wdevF191-2";
				$mail->Body = "<a href='http://localhost/f191-2_PROJECT/verify.php?vkey=$vkey'>Click This Activation Link</a>";
				if(!$mail->send()){
					echo "Mailer Error". $mail->ErrorInfo;
				}
				else{
					echo "<script>alert('Verification Has been Sent Successfully')</script>";
				}
				header('location:success.php');
			}	
		}
	}

?>


<!doctype html>
<html lang="en" class="fullscreen-bg">

<head>
	<title>Register</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/vendor/linearicons/style.css">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="assets/css/main.css">
	<!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
	<link rel="stylesheet" href="assets/css/demo.css">
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
</head>

<body>
	<!-- WRAPPER -->
<div id="wrapper">
	<div class="vertical-align-wrap">
		<div class="vertical-align-middle">
			<div class="auth-box ">
				<div class="left">
					<div class="content">
						<div class="header">
							<div class="logo text-center"><img src="assets/img/logo-dark.png" alt="Klorofil Logo"></div>
							<p class="lead">Register your account</p>
						</div>
						<form class="form-auth-small" action="#" method="POST">
							<div class="form-group">
								<label for="email" class="control-label sr-only">Email</label>
								<input type="email" class="form-control" name="email" placeholder="email"  id="email">
								<span class="text-danger"><?=$error;?><?=$email_check;?></span>
							</div>
							<div class="form-group">
								<label for="password" class="control-label sr-only">Password</label>
								<input type="password" class="form-control" name="password" placeholder="Password"  id="password">
								<span class="text-danger"><?=$error;?><?=$length;?></span>
		                        
							</div>

							<div class="form-group">
								<label for="confrimpassword" class="control-label sr-only">Confirm Password</label>
								<input type="password" class="form-control" name="confirmpassword" placeholder="Password"  id="password">
								<span class="text-danger"><?=$error;?><?=$pwd;?></span>
		                        
							</div>
					
							<button type="submit" name="signup" class="btn btn-primary btn-lg btn-block">Sign Up</button>
						</form>
					</div>
				</div>
				<div class="right">
					<div class="overlay"></div>
					<div class="content text">
						<h1 class="heading">Government Laboratory High School</h1>
						<p>by the F191-2 Developers</p>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
	<!-- END WRAPPER -->
</body>

</html>

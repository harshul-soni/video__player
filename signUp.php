<?php 
require_once("includes/datab.php"); 
require_once("includes/classes/formSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");
	$account=new Account($db);


if(isset($_POST["submitButton"])){
	$firstName=formSanitizer::sanitizeName($_POST["firstName"]);
	$lastName=formSanitizer::sanitizeName($_POST["lastName"]);
	$username=formSanitizer::sanitizeUsername($_POST["username"]);
	$email1=formSanitizer::sanitizeEmail($_POST["email1"]);
	$email2=formSanitizer::sanitizeEmail($_POST["email2"]);
	$password=formSanitizer::sanitizePassword($_POST["password1"]);
	$confirmPass=formSanitizer::sanitizePassword($_POST["password2"]);

	$wasSuccess=$account->register($firstName,$lastName,$username,$email1,$email2,$password,$confirmPass);
	if($wasSuccess){
		$_SESSION["userLoggedIn"]=$username;
		header("Location:index.php");
	}

}
function getInput($name){
	if(isset($_POST[$name])){
		echo $_POST[$name];
	}
}



?>
<!DOCTYPE html>
<html>
<head>
	<title>Empire Video</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="assets/css/index.css" type="text/css"> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</head>

<body>
	<div class="signInContainer">
		<div class="column">
			<div class="header">
				<img src="assets/images/icons/EmpireVideo.png" alt="EmpireVideo" title="EmpireVideo">
				<h3>Sign up</h3>
				<span>to continue to EmpireVideo</span>
				
			</div>
			<div class="loginForm">
				<form action="signUp.php" method="POST">
					<?php echo $account->getError(Constants::$firstNameError); ?>
					<input type="text" name="firstName" placeholder="First Name" autocomplete="off" required value="<?php getInput('firstName');?>">
					<?php echo $account->getError(Constants::$lastNameError); ?>
					<input type="text" name="lastName" placeholder="Last Name" autocomplete="off" required value="<?php getInput('lastName'); ?>">

					<?php echo $account->getError(Constants::$usernameCharError);
					echo $account->getError(Constants::$usernameExists);?>
					<input type="text" name="username" placeholder="Username" autocomplete="off" required value="<?php getInput('username'); ?>">

					<?php echo $account->getError(Constants::$emailMismatch);
					echo $account->getError(Constants::$emailTaken);
					echo $account->getError(Constants::$invalidEmail);?>
					<input type="email" name="email1" placeholder="Email" autocomplete="off" required value="<?php getInput('email1'); ?>">
					<input type="email" name="email2" placeholder="Confirm Email" autocomplete="off" required value="<?php getInput('email2'); ?>">

					<?php echo $account->getError(Constants::$passMismatch);
					echo $account->getError(Constants::$passAlphaError);
					echo $account->getError(Constants::$passLengthError); ?>
					<input type="password" name="password1" placeholder="Password" autocomplete="off" required>
					<input type="password" name="password2" placeholder="Confirm Password" autocomplete="off" required>

					<input type="submit" name="submitButton">
					
				</form>
				
			</div>
			<a class="signInMessage" href="signIn.php">Already Have an account?Login Here</a>
			
		</div>
		
	</div>
	
</body>
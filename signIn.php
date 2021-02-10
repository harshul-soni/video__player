<?php

require_once("includes/datab.php");
require_once("includes/classes/formSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");
$account=new Account($db);


if(isset($_POST['submitButton'])){
	$username=formSanitizer::sanitizeUsername($_POST["username"]);
	$password=formSanitizer::sanitizePassword($_POST["password"]);
	$account=new Account($db);
	$wasSuccess=$account->checkLogIn($username,$password);


	if($wasSuccess){
		$_SESSION["userLoggedIn"]=$_POST["username"];
		header("Location:index.php");
	}
}

function getInput($inputText){
	if(isset($_POST[$inputText])){
		echo $_POST[$inputText];
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
				<h3>Sign In</h3>
				<span>to continue to EmpireVideo</span>
				
			</div>
			<div class="loginForm">
				<form action="signIn.php" method="POST">
					<?php echo $account->getError(Constants::$logInError); ?>
					<input type="text" placeholder="Username" name="username" required autocomplete="off" value="<?php getInput('username'); ?>">
					<input type="password" placeholder="Password" name="password" required>

					<input type="submit" name="submitButton" value="SUBMIT">
				</form>
				
			</div>
			<a class="signInMessage" href="signUp.php">Don't Have an account?Create Here!</a>
			
		</div>
		
	</div>
	
</body>
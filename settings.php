<?php
require_once("includes/header.php");
require_once("includes/classes/formSanitizer.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/User.php");
require_once("includes/classes/SettingsProvider.php");

if(!User::isLoggedIn()){
	header("Location :index.php");
}

$detailsMessage="";
$passwordMessage="";
$settings=new SettingsProvider();


if(isset($_POST["saveUserBtn"])){
	$account=new Account($db);

	$firstName=formSanitizer::sanitizeName($_POST["firstName"]);
	$lastName=formSanitizer::sanitizeName($_POST["lastName"]);
	$email=formSanitizer::sanitizeEmail($_POST["email"]);

	if($account->updateDetails($firstName,$lastName,$email,$userObj->getUsername())){
		$detailsMessage="<div class='alert alert-success'>
			<strong>Details Updated Successfully</strong>

		</div>";
		
	}else{
		$errorMessage=$account->getFirstError();
		$detailsMessage="<div class='alert alert-danger'>
			<strong>Error</strong>.... $errorMessage

		</div>";

	}

}


if(isset($_POST["savePasswordBtn"])){

	$account=new Account($db);

	$oldPassword=formSanitizer::sanitizePassword($_POST["oldPassword"]);
	$newPassword=formSanitizer::sanitizePassword($_POST["newPassword"]);
	$confirmPassword=formSanitizer::sanitizePassword($_POST["confirmPassword"]);
	
	if($account->updatePassword($oldPassword,$newPassword,$confirmPassword,$userObj->getUsername())){
		$passwordMessage="<div class='alert alert-success'>
			<strong>Password Updated Successfully</strong>

		</div>";
		
	}else{
		$errorMessage=$account->getFirstError();
		$passwordMessage="<div class='alert alert-danger'>
			<strong>Error</strong>.... $errorMessage

		</div>";

	}

}

?>

<div class="settingContainer column">
	<div class='formSection'>
		<div class='message'>
			<?php echo $detailsMessage; ?>
		</div>
		<?php 
		$firstName=isset($_POST["firstName"]) ? $_POST["firstName"] : $userObj->getFirstName();
		$lastName=isset($_POST["lastName"]) ? $_POST["lastName"] : $userObj->getLastName();
		$email=	isset($_POST["email"]) ? $_POST["email"] : $userObj->getEmail();



		echo $settings->createUploadForm($firstName,$lastName,$email); ?>

	</div>

	<div class='formSection'>
		<div class='message'>
			<?php echo $passwordMessage; ?>
		</div>
		<?php echo $settings->createPasswordForm(); ?>

	</div>



</div>



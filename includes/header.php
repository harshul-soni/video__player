<?php 
require_once("includes/datab.php");
require_once("includes/classes/User.php");
require_once("includes/classes/Video.php");
require_once("includes/classes/VideoGrid.php");
require_once("includes/classes/ButtonProvider.php");
require_once("includes/classes/VideoGridItem.php");
require_once("includes/classes/SubscriptionProvider.php");
require_once("includes/classes/NavigationMenuProvider.php");
$userLoggedIn;
$userLoggedIn=User::isLoggedIn() ? $userLoggedIn=$_SESSION["userLoggedIn"] : "";
$userObj=new User($db,$userLoggedIn);
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

	<script src="assets/js/commonactions.js"></script>
	<script src="assets/js/userActions.js"></script>
	<script src="assets/js/commentActions.js"></script>
</head>
<body>
	<div id="pagecontainer">
		<div id="mastheadcontainer">
			<button class="navShowhide button"><img src="assets/images/icons/menu.png"></button>
			<a href="index.php" title="EmpireVideo" class="logocontainer" alt="Site Logo">
				<img src="assets/images/icons/EmpireVideo.png">

			</a>

			<div class="searchBarContainer">
				<form action="search.php" method="GET">
					<input type="text" name="term" class="searchbar" placeholder="Search ">
					<button><img src="assets/images/icons/search.png"></button>
				</form>
				
			</div>

			<div class="righticons">
				<a href="upload.php">
					<button>
						<img src="assets/images/icons/upload.png" title="Upload" alt="Upload">
					</button>
				</a>
				<?php echo ButtonProvider::createUserProfileButton($db,$userObj->getUsername()); ?>
				
			</div>
			
		</div>

		<div id="sidenavcontainer" style="display:none">
			<?php 
				$navigationProvider=new NavigationMenuProvider($db,$userObj);
				echo $navigationProvider->create(); 
			?>
			
			
		</div>

		<div id="mainsectioncontainer">
			<div id="mainContentContainer">
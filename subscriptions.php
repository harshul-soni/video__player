<?php
require_once("includes/header.php");

$subscription=new SubscriptionProvider($db,$userObj);
$videos=$subscription->getVideos();

if(!User::isLoggedIn()){
	header("Location:signIn.php");
}


$videoGrid=new VideoGrid($db,$userObj);


?>

<div class="largeVideoGridContainer">
	<?php
		if(sizeof($videos)>0){
			echo $videoGrid->createLarge($videos,"New From Subscriptions",false);
		}else{
			echo "No new videos ";
		}

	?>
	
</div>



<?php require_once("includes/footer.php"); ?>
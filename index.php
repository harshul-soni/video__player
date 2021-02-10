<?php require_once("includes/header.php"); ?>

<div class='videoSection'>
	<?php
		$SubscriptionProvider=new SubscriptionProvider($db,$userObj);
		$subscriptionVideo=$SubscriptionProvider->getVideos();

		$videoGrid=new VideoGrid($db,$userObj);

		if(User::isLoggedIn() && sizeof($subscriptionVideo) >0){
			echo $videoGrid->create($subscriptionVideo,"Subscriptions",false);
		}

		
		echo $videoGrid->create(null,"Recommended",false);

	?>
</div>
				
<?php require_once("includes/footer.php"); ?>
<?php 
require_once("includes/header.php");
require_once("includes/classes/VideoPlayer.php");
require_once("includes/classes/Videodetailsformprovider.php");
require_once("includes/classes/uploadVideoData.php");
require_once("includes/classes/SelectThumbnail.php");

if(!User::isLoggedIn()){
	header("Location:index.php");

}

if(!isset($_GET["videoId"])){
	echo "No Video Selected";
	exit();
}

$video=new Video($db,$_GET["videoId"],$userObj);

if($video->getUploadedBy()!=$userObj->getUsername()){
	echo "Not your Video";
	exit();
}

$detailsMessage="";

if(isset($_POST["saveButton"]))
{
	$videoData=new uploadVideoData(
		null,
		$_POST["inputTitle"],
		$_POST["inputDescription"],
		$_POST["privacyInput"],
		$_POST["categoriesInput"],
		$userObj->getUsername()

	);


	if($videoData->updateDetails($db,$video->getId())){
		$detailsMessage="<div class='alert alert-success'>
			<strong>Details Updated Successfully</strong>

		</div>";
		$video=new Video($db,$_GET["videoId"],$userObj);
		
	}else{
		
		$detailsMessage="<div class='alert alert-danger'>
			<strong>Error... Try Again</strong>

		</div>";

	}

}




?>

<script src="assets/js/editVideoActions.js"></script>


<div class='editVideoContainer column'>

	 <div class='topSection'>
		 	

	 	<?php
	 		$videoPlayer=new VideoPlayer($video);
	 		echo $videoPlayer->create(false);

	 		$SelectThumbnail=new SelectThumbnail($db,$video);
	 		echo $SelectThumbnail->create(); 
	 	?>


	 </div>

	 <div class='updateDetails'>
	 	<div class='message'>
				<?php echo $detailsMessage; ?>
			</div>
	 </div>

	 <div class='bottomSection'>


	 	<?php 

	 		$formProvider=new Videodetailsformprovider($db);
	 		echo $formProvider->createDetailsForm($video);


	 	?>

	 	
	 </div>
</div>
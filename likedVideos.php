<?php
require_once("includes/header.php");
require_once("includes/classes/LikedVideoProvider.php");

$likedVideos=new LikedVideoProvider($db,$userObj);
$videos=$likedVideos->getVideos();

if(!User::isLoggedIn()){
	header("Location:signIn.php");
}


$videoGrid=new VideoGrid($db,$userObj);


?>

<div class="largeVideoGridContainer">
	<?php
		if(sizeof($videos)>0){
			echo $videoGrid->createLarge($videos,"Recently Liked Videos",false);
		}else{
			echo "No Liked videos ";
		}

	?>
	
</div>



<?php require_once("includes/footer.php"); ?>
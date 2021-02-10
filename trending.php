<?php
require_once("includes/header.php");
require_once("includes/classes/TrendingProvider.php");

$trending=new TrendingProvider($db,$userObj);
$videos=$trending->createVideos();


$videoGrid=new VideoGrid($db,$userObj);


?>

<div class="largeVideoGridContainer">
	<?php
		if(sizeof($videos)>0){
			echo $videoGrid->createLarge($videos,"Trending Videos",false);
		}else{
			echo "No trending videos ";
		}

	?>
	
</div>



<?php require_once("includes/footer.php"); ?>
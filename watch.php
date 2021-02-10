<?php require_once("includes/header.php");
require_once("includes/classes/VideoPlayer.php");
require_once("includes/classes/VideoInfo.php");
require_once("includes/classes/Comment.php");
require_once("includes/classes/CommentSection.php");



if(!isset($_GET['id'])){
	echo "Invalid URL ";
}
$video=new Video($db,$_GET['id'],$userObj);
$video->incrementViews()

?>
<script src="assets/js/videoPlayer.js"></script>
<div class="watchLeftColumn">
	<?php $VideoPlayer=new VideoPlayer($video);
	 echo $VideoPlayer->create(false);

	 $videoInfo=new VideoInfo($db,$video,$userObj);
	 echo $videoInfo->create();

	 $commentSection=new CommentSection($db,$video,$userObj);
	 echo $commentSection->create();


	 ?>
	
</div>

<div class="suggestions">
	<?php
		$videoGrid=new VideoGrid($db,$userObj);
		echo $videoGrid->create(null,null,false);


	?>
	
</div>


				
<?php require_once("includes/footer.php"); ?>
<?php require_once("includes/header.php"); 
require_once("includes/classes/SearchResultProvider.php");

if(!isset($_GET["term"]) || $_GET["term"]==""){
	echo "You must enter a search term ";
	exit();
}

$term=$_GET["term"];

if(!isset($_GET["orderBy"]) || $_GET["orderBy"]=="views" )  {
	$orderBy="views";
}else{
	$orderBy="uploadDate";
}

$SearchResultProvider=new SearchResultProvider($db,$userObj);
$videos=$SearchResultProvider->getVideos($term,$orderBy);

$videoGrid=new videoGrid($db,$userObj);


?>

<div class='largeVideoGridContainer'>
	<?php
		$size=sizeof($videos);
		if($size>0){
			echo $videoGrid->createLarge($videos, $size ." Results Found",true);

		}
		else
		{
			echo "Nothing found ..";

		}


	?>
	
</div>




				
<?php require_once("includes/footer.php"); ?>
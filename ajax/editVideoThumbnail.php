<?php

require_once("../includes/datab.php");
if(isset($_POST["thumbnailId"]) && isset($_POST["videoId"])){

	$thumbnailId=$_POST["thumbnailId"];
	$videoId=$_POST["videoId"];
	$query=$db->prepare("UPDATE thumbnails SET selected=0 WHERE videoId=:videoId");
	$query->bindParam(":videoId",$videoId);
	$query->execute();


	$query2=$db->prepare("UPDATE thumbnails SET selected=1 WHERE id=:id");
	$query2->bindParam(":id",$thumbnailId);
	$query2->execute();
	echo "Thumbnail Updated Successfully";


	

}else{

	echo "Error ...";


}


?>
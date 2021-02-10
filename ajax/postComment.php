<?php

require_once("../includes/datab.php");
require_once("../includes/classes/User.php");
require_once("../includes/classes/Comment.php");

	if(isset($_POST['videoId']) && isset($_POST["postedBy"]) && isset($_POST["text"])  ){
		$videoId=$_POST["videoId"];
		$postedBy=$_POST["postedBy"];
		$text=$_POST["text"];
		$responseTo=$_POST["replyTo"];
		$userLoggedObj=new User($db,$_SESSION['userLoggedIn']);
		$query=$db->prepare("INSERT INTO comments (postedBy,videoId,body,responseTo) VALUES (:postedBy,:videoId,:body,:responseTo)");
		$query->bindParam(":postedBy",$postedBy);
		$query->bindParam(":videoId",$videoId);
		$query->bindParam(":body",$text);
		$query->bindParam(":responseTo",$responseTo);
		$query->execute();

		
		$comment=new Comment($videoId,$userLoggedObj,$db,$db->lastInsertId());
		echo $comment->create();
	} 



?>
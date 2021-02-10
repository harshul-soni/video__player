<?php
require_once("../includes/datab.php");
require_once("../includes/classes/User.php");
require_once("../includes/classes/Comment.php");
$videoId=$_POST["videoId"];
$commentId=$_POST["commentId"];
$username=$_SESSION["userLoggedIn"];
$user=new User($db,$username);

$comment=new Comment($videoId,$user,$db,$commentId);
echo $comment->like();

?>
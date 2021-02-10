<?php
require_once("../includes/datab.php");
require_once("../includes/classes/User.php");
require_once("../includes/classes/Video.php");
$videoId=$_POST["videoId"];
$username=$_SESSION["userLoggedIn"];
$user=new User($db,$username);

$video=new Video($db,$videoId,$user);
$video->like();


?>
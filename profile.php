<?php

require_once("includes/header.php");
require_once("includes/classes/ProfileGenerator.php");

if(isset($_GET["username"])){
	$username=$_GET["username"];
}else{
	echo "Invalid Request | Try Logging In ";
	exit();
}

$profile=new ProfileGenerator($db,$userObj,$username);
echo $profile->create();


?>
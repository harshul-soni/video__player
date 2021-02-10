<?php
require_once("includes/header.php");
require_once("includes/classes/uploadVideoData.php");
require_once("includes/classes/videoProccessor.php");
if(!isset($_POST['uploadButton'])){
	echo "Invalid Request";
	exit();
}
$username=$userObj->getUsername();
$videoUploadData=new uploadVideoData($_FILES['inputFile'],$_POST['inputTitle'],$_POST['inputDescription'],$_POST['privacyInput'],$_POST['categoriesInput'],$username);

$videoProccessor=new videoProccessor($db);
$wasSuccessfull=$videoProccessor->upload($videoUploadData);

if($wasSuccessfull){
	echo "File Successfully Uploaded";
}



?>
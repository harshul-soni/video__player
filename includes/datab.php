<?php

ob_start();
session_start();
date_default_timezone_set("Asia/Kolkata");
try{
	$db=new PDO("mysql:dbname=empirevideo;host=localhost", "root" , "");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

}
catch(PDOException $e){
	echo "Connection failed" . $e.getMessage();

}

?>

<?php
require_once("../includes/datab.php");
	if(isset($_POST["userTo"]) && isset($_POST["userFrom"])){
		$userTo=$_POST["userTo"];
		$userFrom=$_POST["userFrom"];
		$queryCheck=$db->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom =:userFrom");
		$queryCheck->bindParam(":userTo",$userTo);
		$queryCheck->bindParam(":userFrom",$userFrom);
		$queryCheck->execute();
		if($queryCheck->rowCount()==0){
			$query=$db->prepare("INSERT INTO subscribers (userTo,userFrom)VALUES (:userTo,:userFrom) ");
			$query->bindParam(":userTo",$userTo);
			$query->bindParam(":userFrom",$userFrom);
			$query->execute();

		}else{
			$query=$db->prepare("DELETE FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
			$query->bindParam(":userTo",$userTo);
			$query->bindParam(":userFrom",$userFrom);
			$query->execute();

		}
		$count=$db->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
		$count->bindParam(":userTo",$userTo);
		$count->execute();
		echo $count->rowCount();



	}


	else
	{
		echo "error";
	}

?>
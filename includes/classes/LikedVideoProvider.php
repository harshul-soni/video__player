<?php 

class LikedVideoProvider {
	private $db,$userObj;
	public function __construct ($db,$userObj){
		$this->db=$db;
		$this->userObj=$userObj;
	}
	public function getVideos(){
		$query=$this->db->prepare("SELECT videoId FROM likes WHERE username = :username AND commentid=0 ORDER BY id DESC");
		$username=$this->userObj->getUsername();
		$query->bindParam(":username",$username);
		$query->execute();

		$videos=array();
		while($row=$query->fetch(PDO::FETCH_ASSOC)){
			$video=new Video($this->db,$row["videoId"],$this->userObj);
			array_push($videos,$video);
		}
		return $videos;
	}
}


?>
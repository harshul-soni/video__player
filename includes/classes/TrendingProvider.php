<?php 

class TrendingProvider {
	private $db,$userObj;
	public function __construct ($db,$userObj){
		$this->db=$db;
		$this->userObj=$userObj;
	}

	public function createVideos(){
		$videos=array();
		$query=$this->db->prepare("SELECT * FROM videos WHERE uploadDate >= now() - INTERVAL 7 DAY ORDER BY views DESC LIMIT 15 ");
		$query->execute();
		while($row=$query->fetch(PDO::FETCH_ASSOC)){
			$video=new Video($this->db,$row,$this->userObj);
			array_push($videos,$video);
		}
		return $videos;
	}
}


?>
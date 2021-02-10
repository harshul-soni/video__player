<?php


class SearchResultProvider{
	private $db ,$userObj;
	public function __construct($db,$userObj){
		$this->db=$db;
		$this->userObj=$userObj;

	}

	public function getVideos($term ,$orderBy){

		$query=$this->db->prepare("SELECT * FROM videos WHERE title LIKE CONCAT('%', :term ,'%') OR uploadedBy LIKE CONCAT('%',:term,'%')
									OR description LIKE CONCAT('%',:term,'%') ORDER BY $orderBy DESC");
		$query->bindParam(":term",$term);
		$query->execute();

		$videos=array();

		while($row=$query->fetch(PDO::FETCH_ASSOC)){
			$video=new Video($this->db,$row,$this->userObj);
			array_push($videos,$video);
		}
		return $videos;

	}
}


?>
<?php


class SelectThumbnail{
	private $db,$video;
	public function __construct($db,$video){
		$this->db=$db;
		$this->video=$video;
	}

	public function create(){
		$thumbnailData=$this->createThumbnailData();

		$html="";
		foreach($thumbnailData as $data){
			$html.=$this->createThumbnailItem($data);
		}
		return "<div class='thumbnailItemsContainer'>
			$html
		</div>";
	}

	private function createThumbnailItem($data){
		$id=$data["id"];
		$videoId=$data["videoId"];
		$filePath=$data["filePath"];
		$selected=$data["selected"] == 1 ? "selected" : "";

		return "<div class='thumbnailItem $selected' onclick='setNewThumbnail($id,$videoId,this)'>
			<img src='$filePath'>
		</div>";
	}

	public function createThumbnailData(){
		$data=array();
		$query=$this->db->prepare("SELECT * FROM thumbnails WHERE videoId=:videoId");
		$query->bindParam(":videoId",$videoId);
		$videoId=$this->video->getId();
		$query->execute();
		while($row=$query->fetch(PDO::FETCH_ASSOC)){
			array_push($data,$row);
		}
		return $data;
	}





}
?>